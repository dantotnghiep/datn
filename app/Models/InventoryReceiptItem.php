<?php

namespace App\Models;

class InventoryReceiptItem extends BaseModel
{
    protected $fillable = ['inventory_receipt_id', 'product_variation_id', 'quantity', 'unit_cost', 'subtotal'];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'inventory_receipt_id' => 'required|exists:inventory_receipts,id',
            'product_variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ];
    }

    public static function getFields()
    {
        return [
            'inventory_receipt_id' => [
                'label' => 'Phiếu nhập',
                'type' => 'select',
                'options' => InventoryReceipt::pluck('receipt_number', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => InventoryReceipt::pluck('receipt_number', 'id')->toArray(),
                'sortable' => true
            ],
            'product_variation_id' => [
                'label' => 'Sản phẩm',
                'type' => 'select',
                'options' => ProductVariation::with('product')->get()->map(function ($variation) {
                    return [
                        'id' => $variation->id,
                        'name' => $variation->product->name . ' - ' . $variation->name
                    ];
                })->pluck('name', 'id')->toArray(),
                'filterable' => true,
                'sortable' => true
            ],
            'quantity' => [
                'label' => 'Số lượng',
                'type' => 'number',
                'sortable' => true
            ],
            'unit_cost' => [
                'label' => 'Đơn giá',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'subtotal' => [
                'label' => 'Thành tiền',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ]
        ];
    }

    public function inventoryReceipt()
    {
        return $this->belongsTo(InventoryReceipt::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
} 