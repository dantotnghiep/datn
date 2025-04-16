<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserLocked;
use App\Http\Controllers\Controller;
use App\Jobs\CheckCancelledOrders;
use App\Models\User;
use App\Models\Order;
use App\Models\UserActivity;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with('activities');

        // Tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Bộ lọc
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'new':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'local':
                    $province = $request->input('province', ''); // Lọc theo tỉnh
                    if (!empty($province)) {
                        $query->whereHas('addresses', function ($q) use ($province) {
                            $q->where('province', $province)->where('is_default', true);
                        });
                    }
                    break;
                case 'top_reviews':
                    // Giả định: Lấy 2 khách hàng đầu tiên (để cứng)
                    $query->orderBy('id', 'asc')->take(2);
                    break;
            }
        }

        // Phân trang (10 khách hàng/trang)
        $customers = $query->with([
            'addresses' => function ($q) {
                $q->where('is_default', true);
            }
        ])
            ->paginate(10)
            ->through(function ($user) {
                // Tổng đơn hoàn thành
                $user->completed_orders = Order::where('user_id', $user->id)
                    ->where('status_id', 4)
                    ->count();

                // Tổng chi tiêu
                $user->total_spent = Order::where('user_id', $user->id)
                    ->where('status_id', 4)
                    ->sum('total_amount');

                // Last Order
                $lastOrder = Order::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $user->last_order = $lastOrder ? $lastOrder->created_at : null;

                return $user;
            });

        // Lấy danh sách tỉnh để lọc Local
        $provinces = \App\Models\Address::select('province')
            ->distinct()
            ->pluck('province')
            ->filter()
            ->values();

        return view('admin.users.clients.index', compact('customers', 'provinces'));
    }

    // Thêm method để thêm khách hàng
    public function store(Request $request)
    {
        // Debug dữ liệu gửi lên
        \Log::info('Add customer request:', $request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:20|unique:users,phone',
                'password' => 'required|string|min:8|confirmed',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role' => 'user',
                'status' => 'active',
            ]);

            return redirect()->route('admin.users.clients.index')->with('success', 'Thêm khách hàng thành công!');
        } catch (\Exception $e) {
            \Log::error('Error adding customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Thêm khách hàng thất bại: ' . $e->getMessage())->withInput();
        }
    }

    public function lock(Request $request, $id)
    {
        $customer = User::where('role', 'user')->findOrFail($id);
        $customer->update(['status' => 'inactive']);
        $customer->save();

        // Ghi lại hoạt động
        UserActivity::create([
            'user_id' => $customer->id,
            'activity_type' => 'locked',
            'reason' => 'Khóa bởi admin',
        ]);

        // Xóa session error
        session()->forget('error');
        return redirect()->route('admin.users.clients.index')->with('success', 'Khóa tài khoản thành công!');
    }

    public function unlock($id)
    {
        $customer = User::where('role', 'user')->findOrFail($id);
        $customer->update(['status' => 'active']);
        $customer->save();
        session()->forget('error');
        return redirect()->route('admin.users.clients.index')->with('success', 'Mở khóa tài khoản thành công!');
    }

    public function show(Request $request, $id)
    {
        $customer = User::with([
            'orders.status',
            'addresses' => function ($q) {
                $q->where('is_default', true);
            },
            'activities'
        ])->where('role', 'user')->findOrFail($id);

      // Lấy danh sách yêu thích với phân trang
      $wishlistItems = Wishlist::where('user_id', $customer->id)
      ->with([
          'product.images' => function ($q) {
              $q->where('is_main', true);
          },
          'product.variations' // Tải variations để lấy giá
      ])
      ->paginate(5); // 5 sản phẩm mỗi trang

        $orderStats = $customer->orders()
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->selectRaw('order_statuses.status_name, COUNT(*) as count')
            ->groupBy('order_statuses.status_name')
            ->pluck('count', 'order_statuses.status_name')
            ->toArray();

        $statuses = ['Pending' => 'Đơn chờ xử lý', 'Completed' => 'Đơn hoàn thành', 'Cancelled' => 'Đơn đã hủy', 'Failed' => 'Đơn thử bại'];
        $stats = [];
        foreach ($statuses as $key => $label) {
            $stats[$label] = $orderStats[$key] ?? 0;
        }

        $totalPaid = $customer->orders()->where('status_id', 4)->sum('total_amount');

        // Dispatch job để kiểm tra hủy đơn
        CheckCancelledOrders::dispatch($customer);

        return view('admin.users.clients.detail', compact('customer', 'stats', 'totalPaid', 'wishlistItems'));
    }

    public function warn(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $customer = User::where('role', 'user')->findOrFail($id);

        UserActivity::create([
            'user_id' => $customer->id,
            'activity_type' => 'warning',
            'reason' => $request->reason,
        ]);

        return redirect()->route('admin.users.clients.detail', $id)->with('success', 'Gửi cảnh báo thành công!');
    }
}
