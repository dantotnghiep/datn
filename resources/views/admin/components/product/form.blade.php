@extends('admin.master')

@section('title', isset($item) ? 'Edit Product' : 'Create Product')

@section('content')
<div class="content">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route($route.'.index') }}">{{ ucfirst(str_replace('admin.', '', $route)) }}</a></li>
            <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Create' }}</li>
        </ol>
    </nav>

    <div class="mb-8">
        <h2 class="mb-2">{{ isset($item) ? 'Edit Product' : 'Create New Product' }}</h2>
        <h5 class="text-body-tertiary fw-semibold">{{ isset($item) ? 'Edit product information, images, and variations' : 'Create a new product with images and variations' }}</h5>
    </div>

    <form action="{{ isset($item) ? route($route.'.update', $item->id) : route($route.'.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <div class="row g-4">
            <!-- Basic Info Card -->
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="category_id">Category</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($fields['category_id']['options'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('category_id', isset($item) ? $item->category_id : '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="name">Product Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($item) ? $item->name : '') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="slug">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', isset($item) ? $item->slug : '') }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="sku">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', isset($item) ? $item->sku : '') }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-bold" for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', isset($item) ? $item->description : '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured Product -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="is_hot">Featured Product</label>
                                <select class="form-select @error('is_hot') is-invalid @enderror" id="is_hot" name="is_hot">
                                    <option value="0" {{ old('is_hot', isset($item) ? $item->is_hot : 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_hot', isset($item) ? $item->is_hot : 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('is_hot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Card -->
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Images</h5>
                        @if(isset($item) && $item->images->count() > 0)
                            <span class="badge bg-info">{{ $item->images->count() }} images</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- New Images -->
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="images">Upload Images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">You can select multiple images. The first image will be the primary image.</div>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Existing Images -->
                        @if(isset($item) && $item->images->count() > 0)
                            <div class="mt-4">
                                <h6 class="mb-3">Current Images</h6>
                                <div class="row g-3" id="product-images">
                                    @foreach($item->images as $image)
                                        <div class="col-md-3 col-sm-6" id="image-container-{{ $image->id }}">
                                            <div class="card h-100 position-relative">
                                                <img src="{{ asset('storage/'.$image->image_path) }}" class="card-img-top" alt="Product Image" style="height: 150px; object-fit: cover;">
                                                <div class="card-body">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" name="existing_images[{{ $image->id }}][is_primary]" value="1" {{ $image->is_primary ? 'checked' : '' }} id="primary-{{ $image->id }}">
                                                        <label class="form-check-label" for="primary-{{ $image->id }}">
                                                            Primary Image
                                                        </label>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-2">
                                                        <span class="input-group-text">Order</span>
                                                        <input type="number" class="form-control" name="existing_images[{{ $image->id }}][order]" value="{{ $image->order }}">
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm w-100 remove-image" data-image-id="{{ $image->id }}">
                                                        <i class="fas fa-trash me-1"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Variations Card -->
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Variations</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-variation-btn">
                            <i class="fas fa-plus me-1"></i> Add Variation
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="variations-container">
                            @if(isset($item) && $item->variations->count() > 0)
                                @foreach($item->variations as $index => $variation)
                                    <div class="variation-row card mb-3" data-variation-id="{{ $variation->id }}">
                                        <div class="card-header bg-light d-flex justify-content-between">
                                            <h6 class="mb-0">Variation #{{ $index + 1 }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-variation">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                                            
                                            <div class="row g-3">
                                                <!-- SKU -->
                                                <div class="col-md-6">
                                                    <label class="form-label">SKU</label>
                                                    <input type="text" class="form-control" name="variations[{{ $index }}][sku]" value="{{ $variation->sku }}" required>
                                                </div>
                                                
                                                <!-- Name -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="variations[{{ $index }}][name]" value="{{ $variation->name }}">
                                                </div>
                                                
                                                <!-- Price -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Price</label>
                                                    <input type="number" step="0.01" class="form-control" name="variations[{{ $index }}][price]" value="{{ $variation->price }}">
                                                </div>
                                                
                                                <!-- Sale Price -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Sale Price</label>
                                                    <input type="number" step="0.01" class="form-control" name="variations[{{ $index }}][sale_price]" value="{{ $variation->sale_price }}">
                                                </div>
                                                
                                                <!-- Stock -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" class="form-control" name="variations[{{ $index }}][stock]" value="{{ $variation->stock }}">
                                                </div>
                                                
                                                <!-- Attributes -->
                                                <div class="col-12">
                                                    <label class="form-label">Attributes</label>
                                                    <div class="row g-2">
                                                        @foreach($attributes as $attribute)
                                                            <div class="col-md-6 col-lg-4">
                                                                <div class="card">
                                                                    <div class="card-header bg-light py-2">
                                                                        <h6 class="mb-0">{{ $attribute->name }}</h6>
                                                                    </div>
                                                                    <div class="card-body py-2">
                                                                        @foreach($attribute->values as $attributeValue)
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" 
                                                                                    type="checkbox" 
                                                                                    id="attr_{{ $index }}_{{ $attributeValue->id }}"
                                                                                    name="variations[{{ $index }}][attribute_values][]" 
                                                                                    value="{{ $attributeValue->id }}"
                                                                                    {{ $variation->attributeValues->contains($attributeValue->id) ? 'checked' : '' }}>
                                                                                    
                                                                                <label class="form-check-label" for="attr_{{ $index }}_{{ $attributeValue->id }}">
                                                                                    {{ $attributeValue->value }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route($route.'.index') }}" class="btn btn-phoenix-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($item) ? 'Update Product' : 'Create Product' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Variation Template (Hidden) -->
<template id="variation-template">
    <div class="variation-row card mb-3">
        <div class="card-header bg-light d-flex justify-content-between">
            <h6 class="mb-0">New Variation</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-variation">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- SKU -->
                <div class="col-md-6">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="variations[__INDEX__][sku]" required>
                </div>
                
                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="variations[__INDEX__][name]">
                </div>
                
                <!-- Price -->
                <div class="col-md-4">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" name="variations[__INDEX__][price]">
                </div>
                
                <!-- Sale Price -->
                <div class="col-md-4">
                    <label class="form-label">Sale Price</label>
                    <input type="number" step="0.01" class="form-control" name="variations[__INDEX__][sale_price]">
                </div>
                
                <!-- Stock -->
                <div class="col-md-4">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="variations[__INDEX__][stock]" value="0">
                </div>
                
                <!-- Attributes -->
                <div class="col-12">
                    <label class="form-label">Attributes</label>
                    <div class="row g-2">
                        @foreach($attributes as $attribute)
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0">{{ $attribute->name }}</h6>
                                    </div>
                                    <div class="card-body py-2">
                                        @foreach($attribute->values as $attributeValue)
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    id="attr_new_{{ $attributeValue->id }}"
                                                    name="variations[__INDEX__][attribute_values][]" 
                                                    value="{{ $attributeValue->id }}">
                                                    
                                                <label class="form-check-label" for="attr_new_{{ $attributeValue->id }}">
                                                    {{ $attributeValue->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
        }
        
        // Handle variation addition
        let variationIndex = {{ isset($item) ? ($item->variations->count() ?? 0) : 0 }};
        const variationTemplate = document.getElementById('variation-template');
        const variationsContainer = document.getElementById('variations-container');
        const addVariationBtn = document.getElementById('add-variation-btn');
        
        if (addVariationBtn && variationTemplate && variationsContainer) {
            addVariationBtn.addEventListener('click', function() {
                let template = variationTemplate.innerHTML;
                template = template.replace(/__INDEX__/g, variationIndex);
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = template;
                const newVariation = tempDiv.firstElementChild;
                
                // Update the heading
                const heading = newVariation.querySelector('h6');
                heading.textContent = `Variation #${variationIndex + 1}`;
                
                // Update attribute IDs to be unique
                const checkboxes = newVariation.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(function(checkbox, i) {
                    checkbox.id = checkbox.id.replace('attr_new_', `attr_${variationIndex}_`);
                    const label = checkbox.nextElementSibling;
                    label.setAttribute('for', checkbox.id);
                });
                
                variationsContainer.appendChild(newVariation);
                variationIndex++;
            });
        }
        
        // Handle variation removal
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-variation') || event.target.closest('.remove-variation')) {
                const button = event.target.classList.contains('remove-variation') ? event.target : event.target.closest('.remove-variation');
                const variationRow = button.closest('.variation-row');
                const variationId = variationRow.dataset.variationId;
                
                if (variationId) {
                    // Add hidden input to mark this variation for deletion
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'remove_variations[]';
                    input.value = variationId;
                    document.querySelector('form').appendChild(input);
                }
                
                variationRow.remove();
            }
        });
        
        // Handle image removal
        const removeImageButtons = document.querySelectorAll('.remove-image');
        
        removeImageButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const imageId = button.dataset.imageId;
                const imageContainer = document.getElementById('image-container-' + imageId);
                
                // Add hidden input to mark this image for deletion
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_images[]';
                input.value = imageId;
                document.querySelector('form').appendChild(input);
                
                // Hide the image container
                imageContainer.remove();
            });
        });
    });
</script>
@endsection 