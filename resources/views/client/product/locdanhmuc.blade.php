@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')

    <!-- ===============peoduct area start=============== -->

    @include('client.layouts.partials.search-top')

    <div class="row">
        @if ($products->isEmpty())
            {{-- <div class="col-12">
                <div class="alert alert-warning text-center">
                    Không có sản phẩm nào trong danh mục này.
                </div>
            </div> --}}
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">Không có sản phẩm nào trong danh mục này.</h4>
            </div>
        @endif
        @foreach ($products as $product)
            @php
                $image = $product->images->first();
                $imageSrc = $image
                    ? (Str::startsWith($image->url, 'http')
                        ? $image->url
                        : asset('storage/' . $image->url))
                    : 'https://via.placeholder.com/640x480.png?text=No+Image';
            @endphp

            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4 border rounded ms-4">
                <div class="product-card-l ">
                    <div class="product-img position-relative">
                        <div class="card-img-top overflow-hidden mt-0">
                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="d-block">
                                <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->

                                <img src="{{ $imageSrc }}" alt="{{ $product->name }}"
                                    class="img-fluid w-100 object-fit-cover product-image" style="max-height: 300px;" />

                            </a>
                        </div>

                        <div class="product-actions position-absolute bottom-0 end-0 p-2">
                            <!-- Icon tim -->
                            <button class="btn p-0 border-0 shadow-none bg-transparent wishlist-toggle mb-1"
                                data-product-id="{{ $product->id }}">
                                <i
                                    class="bi bi-heart{{ auth()->check() &&$product->wishlists()->where('user_id', auth()->id())->exists()? '-fill text-danger': '' }}"></i>
                            </button>
                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                class="text-dark me-2"><i class="flaticon-search"></i></a>

                        </div>
                    </div>

                    <div class="card-body text-center mt-3 mb-3">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text mt-3">
                            @if ($product->variations->count() > 0)
                                @php
                                    $minPrice = $product->variations->min('price');
                                    $minSalePrice = $product->variations->min('sale_price');
                                @endphp
                                @if ($minSalePrice)
                                    <del class="text-muted">{{ number_format($minPrice, 0, ',', '.') }}
                                        đ</del>
                                    <span class="text-danger">{{ number_format($minSalePrice, 0, ',', '.') }}
                                        đ</span>
                                @else
                                    <span class="">{{ number_format($minPrice, 0, ',', '.') }}
                                        đ</span>
                                @endif
                            @else
                                <span class="text-muted">Liên hệ</span>
                            @endif
                        </p>
                        <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                            class="btn btn-sm btn-success position-absolute bottom-0 end-0 m-2 d-none hover-show">
                            <i class="flaticon-shopping-cart"></i>
                        </a>
                        <a href="{{ route('client.product.product-details', $product->id) }}"
                            class="btn btn-sm btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-lg-12 mt-50">
            <div class="custom-pagination d-flex justify-content-center">
                {{-- Khi phân trang, vẫn giữ lại category_slug --}}
                {{ $products->appends(['category_slug' => $slug])->links() }}
            </div>
        </div>
    </div>

    @include('client.layouts.partials.search-bottom')
    <style>
        .product-image {
            transition: transform 0.3s ease;
        }

        .product-img:hover .product-image {
            transform: scale(1.25);
        }

        .sb-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        .sb-category-list {
            list-style: none;
            padding-left: 50px;
            margin: 0;
        }

        .sb-category-list li {
            margin-bottom: 10px;
        }

        .category-form {
            margin: 0;
        }

        .category-button {
            background: none;
            border: none;
            color: #555;
            font-size: 16px;
            text-align: left;
            padding: 5px 10px;
            width: 100%;
            display: block;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .category-button:hover {
            background-color: #f0f0f0;
            color: #000;
            font-weight: 500;
        }

        .sb-title {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: block;
            width: 100%;
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.wishlist-toggle').on('click', function() {
                    let button = $(this);
                    let productId = button.data('product-id');

                    // Debug: Kiểm tra productId
                    console.log('Product ID:', productId);

                    $.ajax({
                        url: `/wishlist/toggle/${productId}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Response:', response); // Debug response
                            if (response.status === 'added') {
                                button.find('i').addClass('bi-heart-fill text-danger').removeClass(
                                    'bi-heart');
                                alert(response.message);
                            } else {
                                button.find('i').addClass('bi-heart').removeClass(
                                    'bi-heart-fill text-danger');
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log('Error:', xhr.responseText); // Debug error
                            alert('Đã có lỗi xảy ra: ' + xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
