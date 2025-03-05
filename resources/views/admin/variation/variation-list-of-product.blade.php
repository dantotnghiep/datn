@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Manage Variations for {{ $product->name }}</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.product.product-list') }}">Product List</a></li>
                        <li>Variations</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="cr-card card-default product-list">
                        <div class="cr-card-content">
                            <div class="table-responsive">
                                <table id="variation_manage" class="table table-striped table-bordered" style="width:100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Variation</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Sale Price</th>
                                            <th>Stock</th>
                                            <th>Sale Time</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($product->variations as $variation)
                                            <tr>
                                                <td>
                                                    @if($variation->attributeValues->isNotEmpty())
                                                        @foreach($variation->attributeValues as $value)
                                                            {{ $value->value }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td><span class="variation-sku">{{ $variation->sku }}</span></td>
                                                <td>{{ number_format($variation->price, 2) }} VNĐ</td>
                                                <td>{{ $variation->sale_price ? number_format($variation->sale_price, 2) . ' VNĐ' : '0.00 VNĐ' }}</td>
                                                <td>{{ $variation->stock }}</td>
                                                <td>{{ $variation->sale_start ? $variation->sale_start->format('d/m/Y') : 'N/A' }} - {{ $variation->sale_end ? $variation->sale_end->format('d/m/Y') : 'N/A' }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $variation->id }}">
                                                        Edit
                                                    </button>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editModal{{ $variation->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $variation->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary text-white">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $variation->id }}">
                                                                        <i class="fas fa-edit me-2"></i>Edit Variation
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <form id="editForm{{ $variation->id }}" action="{{ route('admin.variation.update', $variation->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="sku{{ $variation->id }}" class="form-label fw-bold">SKU</label>
                                                                                <input type="text" class="form-control shadow-sm" id="sku{{ $variation->id }}" name="sku" value="{{ $variation->sku }}">
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="stock{{ $variation->id }}" class="form-label fw-bold">Stock</label>
                                                                                <input type="number" class="form-control shadow-sm" id="stock{{ $variation->id }}" name="stock" value="{{ $variation->stock }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="price{{ $variation->id }}" class="form-label fw-bold">Price (VNĐ)</label>
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control shadow-sm" id="price{{ $variation->id }}" name="price" value="{{ $variation->price }}">
                                                                                    <span class="input-group-text">VNĐ</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="sale_price{{ $variation->id }}" class="form-label fw-bold">Sale Price (VNĐ)</label>
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control shadow-sm" id="sale_price{{ $variation->id }}" name="sale_price" value="{{ $variation->sale_price }}">
                                                                                    <span class="input-group-text">VNĐ</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="sale_start{{ $variation->id }}" class="form-label fw-bold">Sale Start Date</label>
                                                                                <input type="date" class="form-control shadow-sm" id="sale_start{{ $variation->id }}" name="sale_start" value="{{ $variation->sale_start ? $variation->sale_start->format('Y-m-d') : '' }}">
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="sale_end{{ $variation->id }}" class="form-label fw-bold">Sale End Date</label>
                                                                                <input type="date" class="form-control shadow-sm" id="sale_end{{ $variation->id }}" name="sale_end" value="{{ $variation->sale_end ? $variation->sale_end->format('Y-m-d') : '' }}">
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                        <i class="fas fa-times me-1"></i>Close
                                                                    </button>
                                                                    <button type="submit" form="editForm{{ $variation->id }}" class="btn btn-primary">
                                                                        <i class="fas fa-save me-1"></i>Save changes
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                </table>
                            </div>
                        </div>

                        <div class="cr-card-footer text-end">
                            <a href="{{ route('admin.product.product-list') }}" class="btn btn-secondary">Back to Product
                                List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
