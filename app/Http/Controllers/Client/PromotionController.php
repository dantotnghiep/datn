<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    public function getAvailablePromotions()
    {
        try {
            // Only filter by active status
            $promotions = Promotion::where('is_active', 1)
                ->select([
                    'id',
                    'code',
                    'name',
                    'description',
                    'discount_type',
                    'discount_value',
                    'minimum_spend',
                    'maximum_discount',
                    'usage_limit',
                    'usage_count',
                    'starts_at',
                    'expires_at'
                ])
                ->get();

            Log::info('Active promotions found: ' . $promotions->count());

            return response()->json([
                'success' => true,
                'promotions' => $promotions
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading promotions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải mã giảm giá',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
