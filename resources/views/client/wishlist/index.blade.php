@extends('client.layouts.master')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Danh sách yêu thích</h1>

    @if($wishlistItems->isEmpty())
        <p class="text-center text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
    @else
        <div class="row pl-2">
            @foreach($wishlistItems as $wishlistItem)
                @php
                    $product = $wishlistItem->product;
                @endphp
                <div class="col-md-3 mb-4">
                    <div class="card border-0">
                        <div class="product-img position-relative">
                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="d-block">
                                <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->
                                <div class="ratio ratio-1x1">
                                    <img src="{{ asset($product->images->first()->url ?? '/client/assets/images/product/default.jpg') }}"
                                        alt="{{ $product->name }}" class="img-fluid w-100 object-fit-cover" />
                                </div>
                            </a>
                            <div class="product-lavels position-absolute top-0 start-0 p-2">
                                @if ($product->defaultVariation && $product->defaultVariation->sale_price)
                                    <span class="badge bg-danger">Giảm
                                        {{ number_format($product->defaultVariation->price - $product->defaultVariation->sale_price, 0, ',', '.') }}đ</span>
                                @elseif ($product->discount)
                                    <span class="badge bg-danger">Giảm
                                        {{ number_format($product->discount, 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                            <div class="product-actions position-absolute bottom-0 end-0 p-2 d-flex align-items-center">
                                <!-- Icon tim -->
                                <button class="btn btn-light btn-sm wishlist-toggle me-2" data-product-id="{{ $product->id }}">
                                    <i class="bi bi-heart{{ auth()->check() && $product->wishlists()->where('user_id', auth()->id())->exists() ? '-fill text-danger' : '' }}"></i>
                                </button>
                                <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="btn btn-light btn-sm me-2">
                                    <i class="flaticon-search"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">{{ $product->name }}</h5>
                            @if ($product->defaultVariation)
                                <p class="card-text mb-0">
                                    <span class="text-muted text-decoration-line-through me-2">
                                        {{ number_format($product->defaultVariation->price, 0, ',', '.') }}đ
                                    </span>
                                    <span class="text-danger">
                                        {{ number_format($product->defaultVariation->sale_price ?? $product->defaultVariation->price, 0, ',', '.') }}đ
                                    </span>
                                </p>
                            @else
                                <p class="card-text mb-0">
                                    <span class="text-muted text-decoration-line-through me-2">
                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                    </span>
                                    <span class="text-danger">
                                        {{ number_format($product->price - ($product->discount ?? 0), 0, ',', '.') }}đ
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $wishlistItems->links() }}
        </div>
    @endif
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('.wishlist-toggle').on('click', function() {
            let button = $(this);
            let productId = button.data('product-id');

            // Debug: Kiểm tra productId
            console.log('Product ID:', productId);

            $.ajax({
                url: '{{ route("wishlist.toggle", ":productId") }}'.replace(':productId', productId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Response:', response); // Debug response
                    if (response.status === 'added') {
                        button.find('i').addClass('bi-heart-fill text-danger').removeClass('bi-heart');
                        toastr.success(response.message);
                    } else {
                        button.find('i').addClass('bi-heart').removeClass('bi-heart-fill text-danger');
                        toastr.success(response.message);
                        // Xóa card sản phẩm khỏi giao diện nếu xóa khỏi wishlist
                        button.closest('.col-md-3').remove();
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText); // Debug error
                    toastr.error('Đã có lỗi xảy ra: ' + xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
@endsection