@foreach($recentOrders as $order)
<tr>
    <td>#{{ $order->order_number }}</td>
    <td>{{ $order->user_name ?? 'Khách vãng lai' }}</td>
    <td class="text-center">{{ $order->items->count() }}</td>
    <td class="text-end">{{ number_format($order->total, 0, ',', '.') }}đ</td>
    <td class="text-center">
        <span class="badge bg-{{ $order->status->name == 'Completed' ? 'success' : ($order->status->name == 'Pending' ? 'warning' : ($order->status->name == 'Cancelled' ? 'danger' : 'primary')) }}">
            {{ $order->status->name }}
        </span>
    </td>
</tr>
@endforeach 