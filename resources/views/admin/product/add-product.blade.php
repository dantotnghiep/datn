@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Add Product</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li>Add Product</li>
                    </ul>
                </div>
            </div>

            <!-- Debug -->
            @if($errors->any())
                <div style="background-color: #000; color: #fff; padding: 15px; margin-bottom: 20px;">
                    <p>Debug: {{ count($errors->all()) }} errors found</p>
                    @foreach($errors->all() as $error)
                        <p>- {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($errors->any())
                <div id="error-anchor" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <div style="margin-bottom: 5px;">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="cr-card card-default">
                        <div class="cr-card-content">
                            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3 mt-4">
                                    <div class="col-md-6">
                                        <label for="name">Product Name</label>
                                        <input type="text" name="name" class="form-control" id="slug"
                                            onkeyup="ChangeToSlug();" required value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" class="form-control" id="convert_slug"
                                            required value="{{ old('slug') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id">Category</label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    </div>

                                    <hr class="my-4">
                                    <h4>Images</h4>

                                    <div class="col-md-6">
                                        <label for="main_image">Main Image</label>
                                        <input type="file" name="main_image" class="form-control" accept="image/*"
                                            required>
                                        <div class="mt-2" id="main_image_preview"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="additional_images">Additional Images</label>
                                        <input type="file" name="additional_images[]" class="form-control"
                                            accept="image/*" multiple>
                                        <div class="mt-2" id="additional_images_preview"></div>
                                    </div>

                                    <hr class="my-4">
                                    <h4>Attributes</h4>

                                    @foreach ($attributes as $attribute)
                                        <div class="col-md-6">
                                            <label>{{ $attribute->name }}</label>
                                            <select name="attributes[{{ $attribute->id }}][]" class="form-control attribute-select"
                                                data-attribute-id="{{ $attribute->id }}" multiple>
                                                @foreach ($attribute->values as $value)
                                                    <option value="{{ $value->id }}"
                                                        data-value-name="{{ $value->value }}">{{ $value->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach

                                    <hr class="my-4">
                                    <h4>Variations</h4>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary mb-3"
                                            id="generate-variations">Generate Variations</button>
                                        <div id="variations-container">
                                            <!-- Variations will be dynamically added here -->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug logs
            console.log('Errors object:', {!! json_encode($errors->all()) !!});
            console.log('Errors count:', {!! $errors->count() !!});
            console.log('Has errors:', {!! $errors->any() ? 'true' : 'false' !!});
            
            // Image preview functionality
            const mainImageInput = document.querySelector('input[name="main_image"]');
            const additionalImagesInput = document.querySelector('input[name="additional_images[]"]');
            const mainImagePreview = document.getElementById('main_image_preview');
            const additionalImagesPreview = document.getElementById('additional_images_preview');

            mainImageInput.addEventListener('change', function(e) {
                mainImagePreview.innerHTML = '';
                if (this.files && this.files[0]) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(this.files[0]);
                    img.style.maxWidth = '200px';
                    img.style.marginTop = '10px';
                    mainImagePreview.appendChild(img);
                }
            });

            additionalImagesInput.addEventListener('change', function(e) {
                additionalImagesPreview.innerHTML = '';
                if (this.files) {
                    Array.from(this.files).forEach(file => {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.maxWidth = '150px';
                        img.style.marginRight = '10px';
                        img.style.marginTop = '10px';
                        additionalImagesPreview.appendChild(img);
                    });
                }
            });

            // Existing variations functionality
            const generateVariationsBtn = document.getElementById('generate-variations');
            const variationsContainer = document.getElementById('variations-container');
            const attributeSelects = document.querySelectorAll('.attribute-select');

            generateVariationsBtn.addEventListener('click', function() {
                generateVariations();
            });

            function generateVariations() {
                const attributeSelects = document.querySelectorAll('.attribute-select');
                let selectedAttributes = [];

                attributeSelects.forEach(select => {
                    let selectedOptions = Array.from(select.selectedOptions);
                    if (selectedOptions.length > 0) {
                        selectedAttributes.push({
                            attributeId: select.dataset.attributeId,
                            values: selectedOptions.map(option => ({
                                id: option.value,
                                name: option.dataset.valueName
                            }))
                        });
                    }
                });

                if (selectedAttributes.length === 0) {
                    alert('Please select at least one attribute value before generating variations.');
                    return;
                }

                let combinations = generateCombinations(selectedAttributes);
                const variationsContainer = document.getElementById('variations-container');
                variationsContainer.innerHTML = '';

                combinations.forEach((combination, index) => {
                    let variationHtml = `<div class='variation-item border rounded p-3 mb-3'>
                        <div class='d-flex justify-content-between align-items-center mb-3'>
                            <h5 class='mb-0'>Variation ${index + 1}</h5>
                            <button type='button' class='btn btn-danger btn-sm delete-variation'>
                                <i class='bi bi-trash'></i> Delete
                            </button>
                        </div>
                        <div class='row'>
                            <div class='col-md-12 mb-2'>
                                <strong>Attributes:</strong> ${combination.map(attr => `${attr.value}`).join(' / ')}
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>SKU</label>
                                <input type='text' name='variations[${index}][sku]' class='form-control' required>
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Stock</label>
                                <input type='number' name='variations[${index}][stock]' class='form-control' required min='0'>
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Price</label>
                                <input type='number' name='variations[${index}][price]' class='form-control' step='0.01' required min='0'>
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Sale Price</label>
                                <input type='number' name='variations[${index}][sale_price]' class='form-control' step='0.01' min='0'>
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Sale Start</label>
                                <input type='datetime-local' name='variations[${index}][sale_start]' class='form-control'>
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Sale End</label>
                                <input type='datetime-local' name='variations[${index}][sale_end]' class='form-control'>
                            </div>
                        </div>
                        ${combination.map(attr => `<input type='hidden' name='variations[${index}][attribute_values][]' value='${attr.id}'>`).join('')}
                    </div>`;
                    variationsContainer.insertAdjacentHTML('beforeend', variationHtml);
                });

                // Add event listeners for delete buttons
                document.querySelectorAll('.delete-variation').forEach(button => {
                    button.addEventListener('click', function() {
                        const variationItem = this.closest('.variation-item');
                        variationItem.remove();
                        reindexVariations();
                    });
                });
                
                // Add form validation - make sure user can't submit without generating variations
                document.querySelector('form').addEventListener('submit', function(e) {
                    const variations = document.querySelectorAll('.variation-item');
                    if (variations.length === 0) {
                        e.preventDefault();
                        alert('Please generate at least one variation before submitting the form.');
                    }
                });
            }

            // Function to reindex variations after deletion
            function reindexVariations() {
                const variations = document.querySelectorAll('.variation-item');
                variations.forEach((variation, index) => {
                    // Update variation title
                    variation.querySelector('h5').textContent = `Variation ${index + 1}`;

                    // Update input names
                    variation.querySelectorAll('input').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            input.setAttribute('name', name.replace(/variations\[\d+\]/,
                                `variations[${index}]`));
                        }
                    });
                });
            }

            function generateCombinations(attributes) {
                if (attributes.length === 0) return [];

                let combinations = attributes[0].values.map(value => [{
                    id: value.id,
                    value: value.name
                }]);

                for (let i = 1; i < attributes.length; i++) {
                    const temp = [];
                    for (let combination of combinations) {
                        for (let value of attributes[i].values) {
                            temp.push([...combination, {
                                id: value.id,
                                value: value.name
                            }]);
                        }
                    }
                    combinations = temp;
                }

                return combinations;
            }

            // Scroll to error section if there are errors
            if (document.getElementById('error-anchor')) {
                const errorAnchor = document.getElementById('error-anchor');
                const yOffset = -100; // Adjust offset to better display
                const y = errorAnchor.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({top: y, behavior: 'smooth'});
            }
            
            // Lưu vị trí cuộn trước khi submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    sessionStorage.setItem('scrollPosition', window.pageYOffset);
                });
            }
            
            // Phục hồi vị trí cuộn nếu có lỗi xảy ra
            if ({{ $errors->any() ? 'true' : 'false' }}) {
                const savedPosition = sessionStorage.getItem('scrollPosition');
                if (savedPosition) {
                    window.scrollTo(0, savedPosition);
                    sessionStorage.removeItem('scrollPosition');
                }
            }
        });
    </script>
    <style>
        #main_image_preview img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        #additional_images_preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        #additional_images_preview img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>

@endsection