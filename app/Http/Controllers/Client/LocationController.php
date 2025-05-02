<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Thiết lập một địa chỉ làm mặc định
     */
    public function setDefault(Location $location)
    {
        // Kiểm tra xem location có thuộc về user hiện tại không
        if ($location->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền thay đổi địa chỉ này.');
        }

        // Đặt tất cả các location của user thành không mặc định
        Location::where('user_id', Auth::id())->update(['is_default' => false]);

        // Đặt location hiện tại thành mặc định
        $location->is_default = true;
        $location->save();

        return back()->with('success', 'Đã đặt địa chỉ mặc định thành công.');
    }
    
    /**
     * Lấy thông tin địa chỉ theo ID
     */
    public function getLocation(Location $location)
    {
        // Kiểm tra xem location có thuộc về user hiện tại không
        if ($location->user_id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền truy cập'], 403);
        }
        
        return response()->json($location);
    }
} 