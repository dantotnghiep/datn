@extends('client.layouts.master')

@section('content')
<div class="container my-4">
    <h2>Kết quả tìm kiếm{{ $query ? ' cho: "' . $query . '"' : '' }}</h2>
    <!-- Category Filter -->
    <div class="mb-4">
        <form action="{{ route('client.search') }}" method="GET" class="d-flex align-items-center">
            <select name="category_id" class="form-select me-2" style="max-width: 200px;">
                <option value="">Select your choice</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            <input type="text" name="query" class="form-control me-2" value="{{ $query ?? '' }}"
                placeholder="Search Your Products">
            <button type="submit" class="btn btn-dark">SEARCH</button>
            @if ($query || $categoryId)
            <a href="{{ route('client.search') }}" class="ms-3 small">Xóa bộ lọc</a>
            @endif
        </form>
    </div>
    <!-- Results -->
    @if ($products->isEmpty())
    <p>Không tìm thấy sản phẩm nào{{ $query ? ' cho "' . $query . '"' : '' }}.</p>
    @else
    <div class="row">
        @foreach ($products as $product)
        @php
        $image = $product->images->first()->url ?? '/client/assets/images/default.png';
        $variation = $product->variations->first();
        $originalPrice = $variation ? $variation->price : 0;
        $salePrice = $variation ? ($variation->sale_price ?? $originalPrice) : $originalPrice;
        $discount = $originalPrice > $salePrice
        ? round((($originalPrice - $salePrice) / $originalPrice) * 100)
        : null;
        @endphp
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="product-card-l">
                <div class="product-img position-relative overflow-hidden">
                    <a href="{{ route('client.product.product-details', $product->id) }}" class="d-block">
                        <div class="ratio ratio-1x1">
                            <img src="{{ asset($image) }}" alt="{{ $product->name }}"
                                class="img-fluid w-100 object-fit-cover"
                                style="transition: transform 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.1)'"
                                onmouseout="this.style.transform='scale(1)'" />
                        </div>
                    </a>
                    @if ($discount)
                    <div class="product-lavels position-absolute top-0 start-0">
                        <span class="badge bg-danger">Giảm {{ $discount }}%</span>
                    </div>
                    @endif
                </div>
                <div class="product-title text-center py-2">
                    <h3 class="product-title mb-2">
                        <a href="{{ route('client.product.product-details', $product->id) }}"
                            class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                    </h3>
                    <div>
                        @if ($originalPrice > $salePrice)
                        <del class="text-muted">{{ number_format($originalPrice, 0, ',', '.') }}đ</del>
                        <span class="text-danger ms-2">{{ number_format($salePrice, 0, ',', '.') }}đ</span>
                        @else
                        <span class="text-danger">{{ number_format($originalPrice, 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
<style>
    #suggestions {
        max-height: 200px;
        overflow-y: auto;
    }

    .suggestion-item:hover {
        background-color: #f8f9fa;
    }

    .search-section .form-select,
    .search-section .form-control,
    .search-section .btn {
        height: 50px;
        font-size: 1rem;
    }

    .search-section .btn-dark {
        text-transform: uppercase;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            let query = $(this).val().trim();
            let suggestionsContainer = $('#suggestions');

            if (query.length < 2) {
                suggestionsContainer.hide().empty();
                return;
            }

            $.ajax({
                url: '{{ route("client.search-suggestions") }}',
                method: 'GET',
                data: {
                    query: query
                },
                success: function(data) {
                    suggestionsContainer.empty();
                    if (data.length === 0) {
                        suggestionsContainer.hide();
                        return;
                    }

                    data.forEach(function(item) {
                        let label = item.type === 'product' ? item.value : `Category: ${item.value}`;
                        suggestionsContainer.append(
                            `<div class="p-2 suggestion-item" style="cursor: pointer;">${label}</div>`
                        );
                    });
                    suggestionsContainer.show();

                    // Handle suggestion click
                    $('.suggestion-item').on('click', function() {
                        $('#search-input').val($(this).text().replace('Category: ', ''));
                        suggestionsContainer.hide();
                        $('#search-input').closest('form').submit();
                    });
                },
                error: function() {
                    suggestionsContainer.hide().empty();
                }
            });
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#search-input, #suggestions').length) {
                $('#suggestions').hide();
            }
        });
    });
</script>
@endsection