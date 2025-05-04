<tbody>
    @foreach($userOrderStats as $user)
    <tr>
        <td class="py-2">
            <div class="d-flex align-items-center">
                <div class="me-2">
                    <i class="fas fa-user-circle fs-5 text-secondary"></i>
                </div>
                <div>
                    <div class="fw-medium">{{ $user->name }}</div>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
            </div>
        </td>
        <td class="text-center py-2">
            <span class="badge bg-primary">{{ $user->total_orders }}</span>
        </td>
        <td class="text-end py-2 fw-medium">{{ number_format($user->total_spent, 0, ',', '.') }}đ</td>
        <td class="text-center py-2">
            @if($user->completed_orders > 0)
                <span class="badge bg-success">{{ $user->completed_orders }} thành công</span>
            @endif
            @if($user->cancelled_orders > 0)
                <span class="badge bg-danger ms-1">{{ $user->cancelled_orders }} hủy</span>
            @endif
        </td>
        <td class="text-center py-2">{{ date('d/m/Y', strtotime($user->last_order_date)) }}</td>
    </tr>
    @endforeach
</tbody> 