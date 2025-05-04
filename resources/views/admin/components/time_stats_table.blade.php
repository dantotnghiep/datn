<tbody>
    @foreach($dailyStats as $stat)
    <tr>
        <td class="py-2">{{ $stat->label }}</td>
        <td class="text-center py-2">
            <span class="badge bg-primary">{{ $stat->total_orders }}</span>
        </td>
        <td class="text-center py-2">
            <span class="badge bg-success">{{ $stat->completed_orders }}</span>
        </td>
        <td class="text-center py-2">
            <span class="badge bg-danger">{{ $stat->cancelled_orders }}</span>
        </td>
        <td class="text-end py-2 fw-medium">{{ number_format($stat->total_revenue, 0, ',', '.') }}đ</td>
    </tr>
    @endforeach
</tbody> 