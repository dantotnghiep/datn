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
                            <!-- Add Variation Button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariationModal">
                                    <i class="fas fa-plus me-1"></i>Add Variation
                                </button>
                            </div>

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
                                                <td>{{ number_format($variation->price) }} VNĐ</td>
                                                <td>{{ $variation->sale_price ? number_format($variation->sale_price) . ' VNĐ' : '0 VNĐ' }}</td>
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
                            </div>
                        </div>

                        <div class="cr-card-footer text-end">
                            <a href="{{ route('admin.product.product-list') }}" class="btn btn-secondary">Back to Product List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Variation Modal -->
    <div class="modal fade" id="addVariationModal" tabindex="-1" aria-labelledby="addVariationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addVariationModalLabel">
                        <i class="fas fa-plus me-2"></i>Add New Variation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Step 1: Attribute Selection -->
                    <div id="attributeSelectionStep">
                        <h6 class="mb-3">Step 1: Select Attributes</h6>
                        <div class="row">
                            @php
                                $attributes = \App\Models\Attribute::with('values')->get();
                            @endphp
                            @foreach($attributes as $attribute)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ $attribute->name }}</label>
                                    <select class="form-select shadow-sm attribute-select" data-attribute-id="{{ $attribute->id }}" multiple>
                                        @foreach($attribute->values as $value)
                                            <option value="{{ $value->id }}">{{ $value->value }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple values</small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary" id="generateVariationsBtn">
                                <i class="fas fa-magic me-1"></i>Generate Variations
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Variation Details Form -->
                    <div id="variationDetailsStep" style="display: none;">
                        <h6 class="mb-3">Step 2: Enter Variation Details</h6>
                        <form id="addVariationForm" action="{{ route('admin.variation.store', $product->id) }}" method="POST">
                            @csrf
                            <div id="variationFormsContainer">
                                <!-- Variation forms will be generated here -->
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="saveVariationsBtn" style="display: none;">
                        <i class="fas fa-save me-1"></i>Save Variations
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateVariationsBtn = document.getElementById('generateVariationsBtn');
            const saveVariationsBtn = document.getElementById('saveVariationsBtn');
            const attributeSelectionStep = document.getElementById('attributeSelectionStep');
            const variationDetailsStep = document.getElementById('variationDetailsStep');
            const variationFormsContainer = document.getElementById('variationFormsContainer');
            const addVariationForm = document.getElementById('addVariationForm');

            generateVariationsBtn.addEventListener('click', function() {
                const selectedValues = {};
                document.querySelectorAll('.attribute-select').forEach(select => {
                    const attributeId = select.dataset.attributeId;
                    const selectedOptions = Array.from(select.selectedOptions).map(option => ({
                        id: option.value,
                        value: option.text
                    }));
                    if (selectedOptions.length > 0) {
                        selectedValues[attributeId] = selectedOptions;
                    }
                });

                if (Object.keys(selectedValues).length === 0) {
                    alert('Please select at least one attribute value');
                    return;
                }

                // Generate all possible combinations
                const combinations = generateCombinations(selectedValues);
                
                // Clear previous forms
                variationFormsContainer.innerHTML = '';
                
                // Create form for each combination
                combinations.forEach((combination, index) => {
                    const formHtml = `
                        <div class="variation-form mb-4 p-3 border rounded">
                            <h6 class="mb-3">Variation ${index + 1}</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Attributes</label>
                                    <div class="selected-attributes">
                                        ${Object.values(combination).map(attr => `<span class="badge bg-primary me-2">${attr.value}</span>`).join('')}
                                    </div>
                                    ${Object.entries(combination).map(([attrId, value]) => 
                                        `<input type="hidden" name="variations[${index}][attribute_values][]" value="${value.id}">`
                                    ).join('')}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">SKU</label>
                                    <input type="text" class="form-control" name="variations[${index}][sku]" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Price (VNĐ)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="variations[${index}][price]" required min="0">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Sale Price (VNĐ)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="variations[${index}][sale_price]" min="0">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stock</label>
                                    <input type="number" class="form-control" name="variations[${index}][stock]" required min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Sale Period</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="date" class="form-control" name="variations[${index}][sale_start]">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control" name="variations[${index}][sale_end]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    variationFormsContainer.innerHTML += formHtml;
                });

                // Show the variation details step
                attributeSelectionStep.style.display = 'none';
                variationDetailsStep.style.display = 'block';
                saveVariationsBtn.style.display = 'block';
            });

            saveVariationsBtn.addEventListener('click', function() {
                // Validate all required fields
                const requiredFields = variationFormsContainer.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Please fill in all required fields');
                    return;
                }

                // Submit the form
                addVariationForm.submit();
            });

            function generateCombinations(selectedValues) {
                const attributes = Object.entries(selectedValues);
                const combinations = [];

                function generate(current, index) {
                    if (index === attributes.length) {
                        combinations.push({...current});
                        return;
                    }

                    const [attrId, values] = attributes[index];
                    for (const value of values) {
                        current[attrId] = value;
                        generate(current, index + 1);
                    }
                }

                generate({}, 0);
                return combinations;
            }
        });
    </script>
    @endpush
@endsection
