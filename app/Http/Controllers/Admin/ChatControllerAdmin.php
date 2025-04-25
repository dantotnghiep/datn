<?php

namespace App\Http\Controllers\Admin;

use App\Events\RefreshPersonFromClient;
use App\Events\SendAdminMessage;
use App\Events\StopChatForUser;
use App\Helpers\ChatHelper;
use App\Http\Controllers\BotmanController;
use App\Http\Controllers\Controller;
use App\Models\ChatbotMessage;
use App\Models\ChatbotUser;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatControllerAdmin extends Controller
{
    public function index()
    {
        return view('admin.chat.index');
    }

   public function getListPerson(Request $request)
    {
        // Lấy tham số phân trang và tìm kiếm từ request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 8);
        $search = $request->input('search', '');  // Tìm kiếm theo id, user_id hoặc name

        // Tính toán offset
        $offset = ($page - 1) * $perPage;

        // Tạo query lấy danh sách người dùng và tham chiếu tin nhắn mới nhất
        $query = ChatbotUser::select('chatbot_users.*', 'chatbot_messages.created_at as last_message_time')
            ->leftJoin('chatbot_messages', function ($join) {
                $join->on('chatbot_users.id', '=', 'chatbot_messages.chat_id')
                    ->whereRaw('chatbot_messages.created_at = (SELECT MAX(created_at) FROM chatbot_messages WHERE chat_id = chatbot_users.id)');
            });

        // Nếu có từ khóa tìm kiếm, thêm điều kiện lọc
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('chatbot_users.id', 'like', '%' . $search . '%')
                    ->orWhere('chatbot_users.user_id', 'like', '%' . $search . '%')
                    ->orWhere('chatbot_users.name', 'like', '%' . $search . '%')
                    ->orWhere('chatbot_users.email', $search);
            });
        }

        // Sắp xếp theo thời gian tin nhắn mới nhất, nếu không có tin nhắn thì mặc định là cũ nhất
        $query->orderByRaw('IFNULL(chatbot_messages.created_at, chatbot_users.created_at) DESC');

        // Lấy danh sách người dùng
        $users = $query->skip($offset)
            ->take($perPage)
            ->get();

        // Lấy tin nhắn mới nhất cho từng người dùng
        foreach ($users as $user) {
            $lastMessage = ChatbotMessage::where('chat_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastMessage) {
                $sender = is_numeric($lastMessage->user_id) ? 'Nhân viên' : 'Khách';
                $user->lastMessage = $sender . ': ' . \Illuminate\Support\Str::limit($lastMessage->message, 25);                ;
                $user->lastMessage_created_at = $lastMessage->created_at;
            } else {
                $user->lastMessage = 'Không có tin nhắn';
                $user->lastMessage_created_at = null;
            }
        }

        // Đếm tổng số bản ghi
        $total = $query->count();

        return response()->json([
            'success' => true,
            'data' => $users,
            'total' => $total,
            'message' => 'Thành công'
        ]);
    }



    public function getMessages(Request $request)
    {
        $chatId = $request->input('chatId');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $offset = ($page - 1) * $perPage;

        $chatbotUser = ChatbotUser::where('id', $chatId)->first();


        if (!$chatbotUser) {
            return response()->json([
                'success' => false,
                'message' => 'Cuộc trò chuyện không tồn tại'
            ], 200);
        }
        if ($chatbotUser->status == 0 && $chatbotUser->status != 1) {
            $chatbotUser->update(['status' => 1]);
        }
        // Lấy tổng số tin nhắn
        $totalMessages = ChatbotMessage::where('chat_id', $chatbotUser->id)->count();

        // Tính tổng số trang
        $totalPages = ceil($totalMessages / $perPage);
        $messages = ChatbotMessage::where('chat_id', $chatbotUser->id)
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->reverse() // Đảo ngược thứ tự để hiển thị tin nhắn cũ ở đầu trong JavaScript
            ->values();

        $response = [
            'success' => true,
            'total_pages' => $totalPages,
            'room' => [
                $chatbotUser,
            ],
            'data' => [
                'messages' => $messages,
            ]
        ];

        return response()->json($response);
    }
    public function showInfo(Request $request)
    {
        $room = ChatbotUser::find($request->id);
        return view('admin.chat.modal', ['room' => $room]);
    }
    public function postEdit(Request $request)
    {
        $room = ChatbotUser::where('id', $request->id)->first();
        if (!$room) {
            return redirect()->back()->with('error','Không tìm thấy phòng chat');
        }
        DB::beginTransaction();
        try {
            $room->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'note' => $request->note,
                'status' => $request->status
            ]);
            DB::commit();
            return redirect()->back()->with('success','Sửa thông tin thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error','Có lỗi xảy ra trong quá trình sửa thông tin');
        }
    }
    public function sendMessageToUser(Request $request)
    {
        // Validate required inputs
        $roomId = $request->input('roomId');
        $message = $request->input('message');

        if (!$roomId || !$message) {
            return response()->json([
                'success' => false,
                'message' => !$roomId
                    ? 'Vui lòng chọn người nhắn tin hợp lệ'
                    : 'Vui lòng nhập nội dung'
            ]);
        }

        // Fetch the room
        $room = ChatBotUser::find($roomId);
        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Người nhắn không tồn tại. Vui lòng tải lại trang'
            ]);
        }

        $command = $this->checkCommand($message);
        if ($command) {
            return $this->executeCommand($command, $room);
        }
        $chatMessage = $this->createChatMessage($room, $message);
        $this->sendMessageToAdmin($room, $message);

        return response()->json([
            'success' => true,
            'message' => 'Thành công',
            'data' => [
                'id' => $chatMessage->id,
                'user_id' => $chatMessage->user_id,
                'chat_id' => $chatMessage->chat_id,
                'message' => $chatMessage->message,
                'type' => $chatMessage->type,
                'created_at' => $chatMessage->created_at->toDateTimeString()
            ]
        ]);
    }

    private function checkCommand($message)
    {
        $commands = [
            ['command' => '/xinchao', 'description' => 'Lời chào nhanh'],
            ['command' => '/tambiet', 'description' => 'Lời tạm biệt nhanh'],
            ['command' => '/dung', 'description' => 'Dừng cuộc trò chuyện'],
            ['command' => '/xoa', 'description' => 'Xóa cuộc trò chuyện'],
        ];

        foreach ($commands as $cmd) {
            if (strpos($message, $cmd['command']) === 0) {
                return $cmd;
            }
        }

        return null;
    }

    private function executeCommand($command, $room)
    {
        switch ($command['command']) {
            case '/xinchao':
                $responseMessage = 'Xin chào! Mình là ' . auth()->user()->name . ',mã số #' . auth()->user()->id . ' . Bạn đang gặp vấn đề gì vậy ạ?.';
                $this->sendMessageToAdmin($room, $responseMessage);
                $chatMessage = $this->createChatMessage($room, $responseMessage);
                return $this->respondWithMessage($chatMessage);

            case '/tambiet':
                $responseMessage = 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúc bạn một ngày tốt lành.';
                $this->sendMessageToAdmin($room, $responseMessage);
                $chatMessage = $this->createChatMessage($room, $responseMessage);
                return $this->respondWithMessage($chatMessage);

            case '/dung':
                $responseMessage = 'Cuộc trò chuyện đã được đóng. Nếu có vấn đề gì khác vui lòng liên hệ lại với chúng tôi.';
                $this->sendMessageToAdmin($room, $responseMessage);
                $chatMessage = $this->createChatMessage($room, $responseMessage);
                event(new StopChatForUser($room->user_id, $responseMessage));
                return $this->respondWithMessage($chatMessage);

            case '/xoa':
                $responseMessage = 'Cuộc trò chuyện đã được đóng. Nếu có vấn đề gì khác vui lòng liên hệ lại với chúng tôi.';
                $this->sendMessageToAdmin($room, $responseMessage);
                event(new StopChatForUser($room->user_id, $responseMessage));
                $this->deleteChatFunction($room->id);
                return response()->json([
                    'success' => true,
                    'type' => 'delete'
                ]);
            default:
                return $this->respondWithError('Lệnh không hợp lệ.');
        }
    }

    private function createChatMessage($room, $message)
    {
        $chatMessage = new ChatbotMessage;
        $chatMessage->user_id = auth()->user()->id;
        $chatMessage->chat_id = $room->id;
        $chatMessage->message = $message;
        $chatMessage->type = 'reply';
        $chatMessage->save();
        ChatbotUser::where('id', $room->id)->update(['status' => 2]);
        return $chatMessage;
    }

    private function sendMessageToAdmin($room, $message)
    {
        $payload = [
            'userId' => $room->user_id,
            'message' => $message,
            'type' => 'message'
        ];
        $jwt = JWT::encode($payload, "CHAT", 'HS256');
        event(new SendAdminMessage($room->user_id, $jwt, 'message'));
    }
    public function respondWithMessage($chatMessage)
    {
        return response()->json([
            'success' => true,
            'message' => 'Thành công',
            'data' => [
                'id' => $chatMessage->id,
                'user_id' => $chatMessage->user_id,
                'chat_id' => $chatMessage->chat_id,
                'message' => $chatMessage->message,
                'type' => $chatMessage->type,
                'created_at' => $chatMessage->created_at->toDateTimeString()
            ]
        ]);
    }
    public function deleteChatFunction($roomId)
    {
        $chat = ChatBotUser::find($roomId);
        if ($chat) {
            $chat->messages()->delete();
            $chat->delete();
        }
    }
    public function deleteChat($roomId)
    {
        $chat = ChatBotUser::find($roomId);
        if ($chat) {
            $chat->messages()->delete();
            $chat->delete();
            return response()->json([
                'success' => true
            ]);
        }
    }
}
