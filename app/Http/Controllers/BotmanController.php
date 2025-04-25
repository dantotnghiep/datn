<?php

namespace App\Http\Controllers;

use App\Events\MessageFromClient;
use App\Events\RefreshPersonFromClient;
use App\Events\SendAdminMessage;
use App\Models\ChatbotMessage;
use App\Models\ChatbotUser;
use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BotmanController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('/batdau', function (BotMan $botman) {
            $this->sendWelcomeMessage($botman);
        });

        $botman->hears('{message}', function (BotMan $botman, $message) {
            if (strpos($message, 'btn.') !== 0 && strpos($message, '/') !== 0) {
                $lastInteractionTime = Session::get('lastInteractionTime');
                if (Session::get('user_id_chatbot')) {
                    $chatbotUser = ChatbotUser::where('user_id', Session::get('user_id_chatbot'))->first();
                    if (!$chatbotUser) {
                        Session::forget('user_id_chatbot');
                        Session::forget('lastInteractionTime');
                        Session::forget('chatMessages');
                        return response()->json(['success' => false]);
                    }
                }
                if ($lastInteractionTime && Carbon::parse($lastInteractionTime)->diffInMinutes(Carbon::now()) > 30) {
                    Session::forget('user_id_chatbot');
                    Session::forget('lastInteractionTime');
                    Session::forget('chatMessages');
                    return response()->json(['success' => false]);
                } else {
                    if (!Session::get('user_id_chatbot')) {

                    }
                }
            }
        });

        $this->handleConversation($botman);
        $botman->listen();
    }

    public function sendWelcomeMessage(BotMan $botman)
    {
        $botman->reply(
            Question::create('Chào bạn! Vui lòng chọn chức năng nhá!')
                ->addButton(Button::create('Nhắn tin với nhân viên')->value('btn.chatonline'))
                ->addButton(Button::create('Dừng cuộc trò chuyện')->value('btn.dungcuoctrochuyen'))
        );
    }
    public function handleConversation(BotMan $botman)
    {
        $botman->hears('btn.quaylai', function (BotMan $botman) {
            $this->sendWelcomeMessage($botman);
        });
        $botman->hears('btn.dungcuoctrochuyen', function (BotMan $botman) {
            $this->handleStopConversation($botman);
        });
        $botman->hears('btn.chatonline', function (BotMan $botman) {
            $this->handleChatOnline($botman);
        });
    }


    public function handleStopConversation(Botman $botman)
    {
        // // Gửi thông điệp cảm ơn khi kết thúc cuộc hội thoại
        $responseText = "<span>Hội thoại đã kết thúc, cảm ơn Quý khách đã tin dùng sản phẩm dịch vụ của SHOP.</span>";
        $botman->reply(OutgoingMessage::create($responseText));
        // Xóa session chatMessages và lastInteractionTime
        Session::forget('user_id_chatbot');
        Session::forget('lastInteractionTime');
        Session::forget('chatMessages');
        return response()->json(['success' => false]);
    }
    public function StopConversation(Request $request)
    {
        // Xóa session chatMessages và lastInteractionTime
        Session::forget('user_id_chatbot');
        Session::forget('lastInteractionTime');
        Session::forget('chatMessages');
        return response()->json(['success' => true]);
    }
    public function saveChatMessages(Request $request)
    {
        $newMessages = $request->input('message', []);
        $newMessages = array_filter($newMessages, function ($message) {
            if (isset($message['from']) && $message['from'] == 'visitor') {
                $message['text'] = htmlspecialchars($message['text'], ENT_QUOTES, 'UTF-8');
            }
            return !is_null($message) && $message !== '';
        });
        if (!empty($newMessages)) {
            $userId = Session::get('user_id_chatbot');
            Session::put('chatMessages', $newMessages);
            if ($userId) {
                $room = ChatbotUser::where('user_id', $userId)->first();
                if (!$room) {
                    Session::forget('chatMessages');
                    Session::forget('lastInteractionTime');
                    return response()->json(['success' => false]);
                }
                if ($userId == $room->user_id) {
                    $lastMessage = $request->input('lastMessage', NULL);
                    if ($lastMessage) {
                        $chatMessage = new ChatbotMessage();
                        $chatMessage->user_id = $userId;
                        $chatMessage->message = $lastMessage['text'];
                        $chatMessage->chat_id = $room->id;
                        $chatMessage->save();
                        event(new RefreshPersonFromClient($room->id, $chatMessage, $lastMessage, 'refresh'));
                        event(new MessageFromClient($room->id, $lastMessage['text'], 'message', $userId));
                        Session::put('lastInteractionTime', Carbon::now());
                    }
                    return response()->json(['success' => true]);
                }
            }
        }
        return response()->json(['success' => true]);
    }
    public function getChatMessages(Request $request)
    {
        if (Session::get('user_id_chatbot')) {
            $chatbotUser = ChatbotUser::where('user_id', Session::get('user_id_chatbot'))->first();
            if (!$chatbotUser) {
                Session::forget('user_id_chatbot');
                Session::forget('lastInteractionTime');
                Session::forget('chatMessages');
                return response()->json(['success' => false]);
            }
        }
        $messages = Session::get('chatMessages', []);
        $lastInteractionTime = Session::get('lastInteractionTime', null);
        if ($lastInteractionTime == null) {
            return response()->json(['messages' => $messages, 'expired' => false]);
        }
        $expiryTime = $lastInteractionTime->addMinutes(30);
        if (Carbon::now()->greaterThanOrEqualTo($expiryTime)) {
            // Nếu quá 30 phút, xoá dữ liệu tin nhắn và thông báo hết hạn
            Session::forget(['chatMessages', 'lastInteractionTime']);
            return response()->json(['messages' => [], 'expired' => true]);
        }

        // Nếu chưa hết hạn, trả về tin nhắn
        return response()->json(['messages' => $messages, 'expired' => false]);
    }
    function generateUniqueUserId()
    {
        if (!Session::has('user_id_chatbot')) {
            $uniqueUserId = uniqid('user_', true);
            Session::put('user_id_chatbot', $uniqueUserId);
            Session::put('session_start_time_chatbot', time());
        } else {
            $uniqueUserId = Session::get('user_id_chatbot');
            $sessionStartTime = Session::get('session_start_time_chatbot');
            $currentTime = time();
            if (($currentTime - $sessionStartTime) > 1800) {
                $uniqueUserId = uniqid('user_', true);
                Session::put('user_id_chatbot', $uniqueUserId);
                Session::put('session_start_time_chatbot', $currentTime);
            }
        }

        return $uniqueUserId;
    }
    public function saveUserInfo($botman)
    {
        $userId = $this->generateUniqueUserId();
        $user = ChatbotUser::where('user_id', $userId)->first();

        if (!$user) {
            $user = ChatbotUser::create([
                'user_id' => $userId,
                'name' => 'Người dùng',
                'read_at' => null,
            ]);

            event(new MessageFromClient(null, $user, 'get'));
        }
    }
    public function handleChatOnline($botman)
    {
        // Lưu thông tin người dùng và gửi sự kiện
        $this->saveUserInfo($botman);
        event(new SendAdminMessage(null, null, 'start'));

        // Phản hồi khi hệ thống đang hoạt động
        $responseText = "<span>Vui lòng đợi trong giây lát hệ thống sẽ kết nối bạn với nhân viên...</span>";
        $botman->reply(OutgoingMessage::create($responseText));
    }

    public function checkId(Request $request)
    {
        $userId = Session::get('user_id_chatbot');

        if (!$userId) {
            return response()->json(['success' => false, 'userId' => false]);
        }
        $check = ChatbotUser::where('user_id', $userId)->exists();
        return response()->json([
            'success' => $check,
            'userId' => $check ? $userId : false
        ]);
    }
}
