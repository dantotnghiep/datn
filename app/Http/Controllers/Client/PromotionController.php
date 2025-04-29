<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function getAvailablePromotions()
    {
        try {
            $now = now();
            
            // Get base query
            $query = Promotion::where('is_active', 1);
            
            // Get all promotions first for debugging
            $allPromotions = $query->get();
            
            // Then add date conditions
            $promotions = $query->where(function($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function($query) use ($now) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', $now);
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                    ->orWhereRaw('COALESCE(usage_count, 0) < usage_limit');
            })
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

            return response()->json([
                'success' => true,
                'promotions' => $promotions,
                'debug' => [
                    'total' => $promotions->count(),
                    'all_active_count' => $allPromotions->count(),
                    'now' => $now->format('Y-m-d H:i:s'),
                    'conditions' => [
                        'is_active' => 1,
                        'time_range' => [
                            'start' => $now->format('Y-m-d H:i:s'),
                            'end' => $now->format('Y-m-d H:i:s')
                        ]
                    ],
                    'all_promotions' => $allPromotions->map(function($promo) {
                        return [
                            'code' => $promo->code,
                            'is_active' => $promo->is_active,
                            'starts_at' => optional($promo->starts_at)->format('Y-m-d H:i:s'),
                            'expires_at' => optional($promo->expires_at)->format('Y-m-d H:i:s'),
                            'usage_count' => $promo->usage_count,
                            'usage_limit' => $promo->usage_limit
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải mã giảm giá',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 