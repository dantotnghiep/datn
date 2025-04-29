<?php

namespace App\Models;

class OrderCancellation extends BaseModel
{
    protected $fillable = ['order_id', 'reason', 'notes'];

    public static function rules($id = null)
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public static function getFields()
    {
        return [
            'order_id' => [
                'label' => 'Order Number',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true,
                'formatter' => function ($value, $item) {
                    return $item->order ? $item->order->order_number : $value;
                }
            ],
            'customer_info' => [
                'label' => 'Customer',
                'type' => 'text',
                'searchable' => false,
                'sortable' => false,
                'formatter' => function ($value, $item) {
                    if (!$item->order) return '-';
                    return $item->order->user_name . ' (' . $item->order->user_phone . ')';
                }
            ],
            'order_status' => [
                'label' => 'Order Status',
                'type' => 'text',
                'searchable' => false,
                'sortable' => false,
                'formatter' => function ($value, $item) {
                    if (!$item->order) return '-';

                    // Lấy status_id trực tiếp từ order
                    $statusId = $item->order->getRawOriginal('status_id') ?? 0;
                    $statusName = '';

                    // Xác định tên trạng thái theo ID
                    switch ($statusId) {
                        case 1:
                            $statusName = 'Pending';
                            $statusClass = 'warning';
                            break;
                        case 2:
                            $statusName = 'Completed';
                            $statusClass = 'success';
                            break;
                        case 3:
                            $statusName = 'Shipping';
                            $statusClass = 'info';
                            break;
                        case 4:
                            $statusName = 'Cancelled';
                            $statusClass = 'danger';
                            break;
                        case 5:
                            $statusName = 'Refunded';
                            $statusClass = 'danger';
                            break;
                        default:
                            $statusName = 'Unknown';
                            $statusClass = 'secondary';
                    }

                    return '<span class="badge bg-' . $statusClass . '">' . $statusName . '</span>';
                }
            ],
            'reason' => [
                'label' => 'Cancellation Reason',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'notes' => [
                'label' => 'Notes',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'created_at' => [
                'label' => 'Requested Date',
                'type' => 'datetime',
                'sortable' => true
            ],
            'action' => [
                'label' => 'Actions',
                'type' => 'text',
                'searchable' => false,
                'sortable' => false
            ],
        ];
    }

    // Phương thức accessor để lấy order number
    public function getOrderIdAttribute($value)
    {
        $order = Order::find($value);
        return $order ? $order->order_number : $value;
    }

    // Accessor để lấy thông tin khách hàng
    public function getCustomerInfoAttribute()
    {
        if (!$this->order) return '-';
        return $this->order->user_name . ' (' . $this->order->user_phone . ')';
    }

    // Accessor để lấy trạng thái đơn hàng
    public function getOrderStatusAttribute()
    {
        if (!$this->order) return '-';
        
        // Lấy status_id trực tiếp từ order
        $statusId = $this->order->getRawOriginal('status_id') ?? 0;
        $statusName = '';
        
        // Xác định tên trạng thái theo ID
        switch ($statusId) {
            case 1:
                $statusName = 'Pending';
                $statusClass = 'warning';
                break;
            case 2:
                $statusName = 'Completed';
                $statusClass = 'success';
                break;
            case 3:
                $statusName = 'Shipping';
                $statusClass = 'info';
                break;
            case 4:
                $statusName = 'Cancelled';
                $statusClass = 'danger';
                break;
            case 5:
                $statusName = 'Refunded';
                $statusClass = 'danger';
                break;
            default:
                $statusName = 'Unknown';
                $statusClass = 'secondary';
        }
        
        return '<span class="badge bg-' . $statusClass . '">' . $statusName . '</span>';
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
