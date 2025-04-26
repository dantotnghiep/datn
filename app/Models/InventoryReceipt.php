<?php

namespace App\Models;

class InventoryReceipt extends BaseModel
{
    protected $fillable = ['receipt_number', 'user_id', 'supplier_name', 'supplier_contact', 'total_amount', 'notes'];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'receipt_number' => 'required|string|max:255|unique:inventory_receipts,receipt_number,' . $id,
            'user_id' => 'required|exists:users,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ];
    }

    public static function getFields()
    {
        return [
            'receipt_number' => [
                'label' => 'Mã phiếu nhập',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'user_id' => [
                'label' => 'Nhân viên tạo',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'supplier_name' => [
                'label' => 'Tên nhà cung cấp',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'supplier_contact' => [
                'label' => 'Liên hệ nhà cung cấp',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'total_amount' => [
                'label' => 'Tổng tiền',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'notes' => [
                'label' => 'Ghi chú',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'created_at' => [
                'label' => 'Ngày tạo',
                'type' => 'datetime',
                'sortable' => true
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryReceiptItem::class);
    }
} 