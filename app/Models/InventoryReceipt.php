<?php

namespace App\Models;

class InventoryReceipt extends BaseModel
{
    protected $fillable = ['receipt_number', 'user_id', 'supplier_name', 'supplier_contact', 'total_amount', 'notes', 'status'];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];
    
    /**
     * Override để vô hiệu hóa tính năng tạo slug
     */
    protected function slugExists($slug)
    {
        return false; // Bỏ qua việc kiểm tra slug vì model này không sử dụng slug
    }

    /**
     * Override để vô hiệu hóa việc cập nhật slug
     */
    protected static function bootHasSlug()
    {
        // Không làm gì để vô hiệu hóa hành vi
    }

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
                'label' => 'Số phiếu nhập kho',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'user_id' => [
                'label' => 'Nhân viên',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'supplier_name' => [
                'label' => 'Nhà cung cấp',
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
            ],
            'status' => [
                'label' => 'Trạng thái',
                'type' => 'select',
                'options' => ['pending' => 'Chờ xử lý', 'completed' => 'Đã xử lý', 'cancelled' => 'Đã hủy'],
                'sortable' => true
            ]
     
        ];
    }

    public function getUserIdAttribute($value)
    {
        $user = User::find($value);
        return $user ? $user->name : $value;
    }
    public function user()
    {
        return $this->belongsTo(User::class)->whereIn('role', [1, 2]);
    }

    public function items()
    {
        return $this->hasMany(InventoryReceiptItem::class);
    }
}
