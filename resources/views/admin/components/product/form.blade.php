@extends('admin.master')

@section('styles')
    <style>
        .product-form-heading {
            color: #344767;
            font-weight: 600;
            margin-bottom: 1rem;
            position: relative;
            padding-left: 0.75rem;
            border-left: 4px solid #5e72e4;
        }

        .description-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .custom-editor {
            border: none;
            padding: 1rem;
            min-height: 200px;
        }

        .tox-tinymce {
            border-radius: 8px !important;
            border: 1px solid #e9ecef !important;
        }

        .select-category {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 0.5rem;
            transition: all 0.2s ease;
        }

        .select-category:focus {
            border-color: #5e72e4;
            box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
        }

        /* Better styling for the placeholder */
        .form-control::placeholder {
            color: #adb5bd;
            opacity: 0.7;
        }

        /* Attribute value buttons styling */
        .attribute-value-btn {
            display: block;
            width: 100%;
            padding: 0.75rem 0.5rem;
            text-align: center;
            margin-bottom: 0.5rem !important;
            margin-right: 0 !important;
            transition: all 0.2s;
            font-weight: 500;
            border-radius: 0.375rem;
            color: #5e72e4;
            background-color: #f8f9fa;
            border: 1px solid #edf2f9;
        }

        .attribute-value-btn.active {
            background-color: #5e72e4;
            color: white;
            border-color: #5e72e4;
        }

        #values-display-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        /* Size chips styling */
        .size-chip {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            transition: all 0.2s;
            cursor: default;
        }

        .size-chip .btn-close {
            opacity: 0.6;
            cursor: pointer;
        }

        .size-chip .btn-close:hover {
            opacity: 1;
        }

        @media (min-width: 576px) {
            #values-display-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 992px) {
            #values-display-container {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .variant-values-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .variant-value-item {
            cursor: pointer;
            transition: all 0.2s;
        }

        .variant-value-item:hover {
            background-color: #f8f9fa;
        }

        .variant-value-item.selected {
            background-color: #4e73df;
            color: white;
        }

        .variant-value-item:last-child {
            border-bottom: none !important;
        }

        /* Selected values styling */
        .selected-value-chip {
            background-color: #fff;
            border-radius: 16px;
            padding: 2px 8px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            color: #333;
        }

        .variant-values-display .selected-value-chip {
            background-color: #fff;
            color: #333;
        }

        .selected-value-chip .btn-close {
            width: 0.75em;
            height: 0.75em;
            opacity: 0.7;
            margin-left: 5px;
            font-size: 0.75rem;
            padding: 0;
        }

        .selected-values-container {
            background-color: #f5f7ff;
            border-radius: 0 0 8px 8px;
        }

        .variant-values-container {
            overflow: hidden;
        }

        .variant-values-display {
            background-color: #5e72e4;
            color: white;
            padding: 8px;
            border-radius: 8px;
        }

        /* Dropzone styling */
        .dropzone {
            border: 2px dashed #5e72e4;
            border-radius: 8px;
            background: #f8f9fa;
            min-height: 150px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            background: #eef0fa;
        }

        .dz-preview {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
            vertical-align: top;
        }

        .dz-image {
            border-radius: 8px;
            overflow: hidden;
            width: 120px;
            height: 120px;
            position: relative;
            display: block;
            z-index: 10;
        }

        .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dz-remove {
            font-size: 12px;
            text-align: center;
            display: block;
            cursor: pointer;
            color: #5e72e4;
            margin-top: 5px;
        }

        .dz-message {
            padding: 2rem 1rem;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/choices/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dropzone/dropzone.min.css') }}">
@endsection


@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Create' }}</li>
            </ol>
        </nav>
        <form class="mb-9" method="POST" action="{{ isset($item) ? route('admin.products.update', $item->id) : route('admin.products.store') }}" enctype="multipart/form-data" id="product-form">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif
            <div class="row g-3 flex-between-end mb-5">
                <div class="col-auto">
                    <h2 class="mb-2">{{ isset($item) ? 'Edit product' : 'Add a product' }}</h2>
                    <h5 class="text-body-tertiary fw-semibold">Complete all required fields</h5>
                </div>
                <div class="col-auto">
                    <a class="btn btn-phoenix-secondary me-2 mb-2 mb-sm-0" href="{{ route('admin.products.index') }}">Cancel</a>
                    <button class="btn btn-phoenix-primary me-2 mb-2 mb-sm-0" type="submit" name="action" value="draft">Save draft</button>
                    <button class="btn btn-primary mb-2 mb-sm-0" type="submit" id="publish-btn" name="action" value="publish">Publish product</button>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-12 col-xl-8">
                    <h4 class="mb-3">Product Title</h4>
                    <input class="form-control mb-5" type="text" name="name" placeholder="Write title here..." value="{{ $item->name ?? old('name') }}" required />

                    <div class="mb-6">
                        <h4 class="mb-3 product-form-heading">Product Description</h4>
                        <div class="description-container">
                            <textarea class="tinymce form-control custom-editor" name="description"
                                data-tinymce='{"height":"15rem","placeholder":"Write a description here...","skin":"oxide","content_css":"default","menubar":false,"statusbar":false,"toolbar":"bold italic underline | bullist numlist | link image | formatselect","plugins":"link image lists"}'>{{ $item->description ?? old('description') }}</textarea>
                        </div>
                    </div>
                    <h4 class="mb-3">Display images</h4>
                    <div class="mb-3">
                        @if(isset($item) && $item->images->count() > 0)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5>Current Images</h5>
                                    <div class="d-flex flex-wrap">
                                        @foreach($item->images as $image)
                                            <div class="position-relative me-2 mb-2">
                                                <img src="{{ Storage::url($image->image_path) }}" alt="{{ $item->name }}" class="img-thumbnail" style="height:100px; width:100px; object-fit:cover;">
                                                <div class="position-absolute top-0 end-0">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="existing_images[{{ $image->id }}][is_primary]" value="1" {{ $image->is_primary ? 'checked' : '' }}>
                                                        <label class="form-check-label text-white">Primary</label>
                                                    </div>
                                                    <input type="hidden" name="existing_images[{{ $image->id }}][order]" value="{{ $image->order }}">
                                                    <div class="form-check">
                                                        <input class="form-check-input delete-image" type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                                                        <label class="form-check-label text-white">Remove</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="dropzone dropzone-multiple p-0 mb-5" id="product-images-upload">
                        <div class="fallback"><input name="images[]" type="file" multiple /></div>
                        <div class="dz-message text-body-tertiary text-opacity-85" data-dz-message="data-dz-message">
                            Drag your photos here<span class="text-body-secondary px-1">or</span>
                            <button class="btn btn-link p-0" type="button">Browse from device</button><br />
                            <img class="mt-3 me-2" src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/icons/image-icon.png') }}" width="40" alt="" />
                        </div>
                        <div class="dz-preview-container d-flex flex-wrap mt-3" id="image-preview-container"></div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="row g-2">
                        <div class="col-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Organize</h4>
                                    <div class="row gx-3">
                                        <div class="col-12 col-sm-6 col-xl-12">
                                            <div class="mb-4">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="mb-0 text-body-highlight me-2">Category</h5><a
                                                        class="fw-bold fs-9"
                                                        href="{{ route('admin.categories.create') }}">Add new category</a>
                                                </div>
                                                <select class="form-select mb-3" name="category_id" aria-label="category" required>
                                                    <option value="">Select Category</option>
                                                    @foreach (\App\Models\Category::all() as $category)
                                                        <option value="{{ $category->id }}" {{ (isset($item) && $item->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-xl-12">
                                            <div class="mb-4">
                                                <h5 class="mb-0 text-body-highlight mb-2">Featured Product</h5>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" id="is_hot" name="is_hot" type="checkbox" value="1" {{ (isset($item) && $item->is_hot) || old('is_hot') ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="is_hot">Mark as featured</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Variants</h4>
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6 col-xl-12" id="variants-container">
                                            <div
                                                class="border-bottom border-translucent border-dashed border-sm-0 border-bottom-xl pb-4 variant-option" data-option="1">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="text-body-highlight me-2">Option 1</h5>
                                                    <a class="fw-bold fs-9 remove-option" href="javascript:void(0)">Remove</a>
                                                </div>
                                                <select class="form-select mb-3 attribute-select">
                                                    <option value="">Select attribute</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                    @endforeach
                                                </select>

                                                <div class="variant-values-container border rounded">
                                                    <div class="variant-values-list p-2" id="variant-values-option-1">
                                                        <!-- Values will be populated here dynamically -->
                                                    </div>
                                                    <div class="selected-values-container p-2 border-top d-none">
                                                        <div class="variant-values-display p-2 mb-2">
                                                            <div class="d-flex flex-wrap gap-2 selected-values"></div>
                                                        </div>
                                                        <div class="text-muted mt-2 no-values-text">No choices to choose from</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button id="add-option-btn" class="btn btn-phoenix-primary w-100 mt-3" type="button">Add another option</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hidden input to store variants data -->
            <input type="hidden" id="variants-data" name="variants" value="">
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Dropzone
            if (typeof Dropzone !== 'undefined') {
                Dropzone.autoDiscover = false;

                let myDropzone = new Dropzone("#product-images-upload", {
                    url: "{{ isset($item) ? route('admin.products.update', $item->id) : route('admin.products.store') }}",
                    paramName: "images",
                    acceptedFiles: "image/*",
                    addRemoveLinks: true,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 10,
                    maxFiles: 10,
                    maxFilesize: null,
                    previewsContainer: "#image-preview-container",
                    clickable: "#product-images-upload",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    init: function() {
                        let myDropzone = this;
                        let form = document.getElementById('product-form');

                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Collect variant data
                            collectVariantData();

                            // Create FormData
                            let formData = new FormData(form);

                            // Add queued files to FormData
                            let queuedFiles = myDropzone.getQueuedFiles();
                            queuedFiles.forEach(function(file) {
                                formData.append('images[]', file);
                            });

                            // Get the form method and URL
                            let method = form.getAttribute('method').toUpperCase();
                            let url = form.getAttribute('action');

                            // If it's PUT request, we need to append _method field
                            if (method === 'PUT') {
                                formData.append('_method', 'PUT');
                            }

                            // Send AJAX request
                            fetch(url, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = data.redirect;
                                } else {
                                    alert(data.message || 'Error saving product');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error saving product. Please try again.');
                            });
                        });

                        // File added event
                        this.on("addedfile", function(file) {
                            console.log("File added:", file.name);
                        });

                        // File removed event
                        this.on("removedfile", function(file) {
                            console.log("File removed:", file.name);
                        });

                        // Error handling
                        this.on("error", function(file, errorMessage) {
                            console.error("Error with file:", file.name, errorMessage);
                            if (file.previewElement) {
                                let errorDisplay = file.previewElement.querySelector('[data-dz-errormessage]');
                                if (errorDisplay) {
                                    errorDisplay.textContent = errorMessage;
                                }
                                file.previewElement.classList.add('dz-error');
                            }
                        });

                        // Display existing images (for edit mode)
                        @if(isset($item) && $item->images->count() > 0)
                            @foreach($item->images as $image)
                                let mockFile = {
                                    name: "{{ basename($image->image_path) }}",
                                    size: 12345,
                                    accepted: true,
                                    status: "success",
                                    id: {{ $image->id }},
                                    is_primary: {{ $image->is_primary ? 'true' : 'false' }}
                                };

                                myDropzone.emit("addedfile", mockFile);
                                myDropzone.emit("thumbnail", mockFile, "{{ Storage::url($image->image_path) }}");
                                myDropzone.emit("complete", mockFile);

                                if (mockFile.is_primary) {
                                    let primaryBadge = document.createElement('div');
                                    primaryBadge.className = 'position-absolute top-0 start-0 bg-primary text-white px-2 py-1 small rounded-bottom';
                                    primaryBadge.style.zIndex = '15';
                                    primaryBadge.textContent = 'Primary';
                                    mockFile.previewElement.appendChild(primaryBadge);
                                }
                            @endforeach
                        @endif
                    }
                });
            }

            // Initialize flatpickr
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datetimepicker", {
                    dateFormat: "d/m/Y",
                    disableMobile: true
                });
            }

            // Function to collect variant data
            function collectVariantData() {
                // Collect all variant options data
                const variantOptions = [];

                document.querySelectorAll('.variant-option').forEach(optionEl => {
                    const optionNumber = optionEl.getAttribute('data-option');
                    const attributeSelect = optionEl.querySelector('.attribute-select');
                    const attributeId = attributeSelect.value;
                    const attributeName = attributeSelect.options[attributeSelect.selectedIndex]?.text || '';

                    if (!attributeId) return;

                    const selectedValues = [];
                    optionEl.querySelectorAll('.selected-value-chip').forEach(chip => {
                        selectedValues.push({
                            id: chip.getAttribute('data-value-id'),
                            value: chip.querySelector('span').innerText
                        });
                    });

                    if (selectedValues.length === 0) return;

                    variantOptions.push({
                        option: optionNumber,
                        attribute_id: attributeId,
                        attribute_name: attributeName,
                        values: selectedValues
                    });
                });

                // Update the hidden input with variant data
                document.getElementById('variants-data').value = JSON.stringify(variantOptions);
                console.log("Variant data collected:", document.getElementById('variants-data').value);
            }

            // Handle form submission - collect variant data
            document.querySelector('form').addEventListener('submit', function(e) {
                collectVariantData();
            });
        });
    </script>
    {{--  variants handling --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantsContainer = document.getElementById('variants-container');
            const addOptionBtn = document.getElementById('add-option-btn');
            const publishBtn = document.getElementById('publish-btn');
            let optionCount = 1;
            // Track selected attributes
            const selectedAttributes = new Set();

            // Function to initialize attribute select event listeners
            function initAttributeSelect(select) {
                select.addEventListener('change', function() {
                    const attributeId = this.value;
                    const optionEl = this.closest('.variant-option');
                    const optionNumber = optionEl.getAttribute('data-option');
                    const valuesContainer = document.getElementById(`variant-values-option-${optionNumber}`);
                    const selectedValuesContainer = optionEl.querySelector('.selected-values-container');
                    const selectedValues = optionEl.querySelector('.selected-values');
                    const noValuesText = optionEl.querySelector('.no-values-text');
                    const valuesDisplay = optionEl.querySelector('.variant-values-display');

                    // Update selected attributes tracking
                    const previousAttributeId = select.getAttribute('data-previous-value');
                    if (previousAttributeId) {
                        selectedAttributes.delete(previousAttributeId);
                    }

                    if (attributeId) {
                        selectedAttributes.add(attributeId);
                        select.setAttribute('data-previous-value', attributeId);
                    } else {
                        select.removeAttribute('data-previous-value');
                    }

                    // Update all selects to reflect current selections
                    updateAttributeSelects();

                    // Clear existing values
                    valuesContainer.innerHTML = '';
                    selectedValues.innerHTML = '';
                    selectedValuesContainer.classList.add('d-none');
                    noValuesText.classList.remove('d-none');
                    if (valuesDisplay) valuesDisplay.classList.add('d-none');

                    // Always show the values container when changing attributes
                    valuesContainer.classList.remove('d-none');

                    if (!attributeId) return;

                    // Get values for selected attribute and display them
                    const attributeValues = [];

                    @foreach ($attributeValues as $value)
                        if ({{ $value->attribute_id }} == attributeId) {
                            attributeValues.push({
                                id: {{ $value->id }},
                                value: "{{ $value->value }}"
                            });
                        }
                    @endforeach

                    // Create and append value items
                    attributeValues.forEach(value => {
                        const valueItem = document.createElement('div');
                        valueItem.classList.add('variant-value-item', 'p-2', 'border-bottom');
                        valueItem.setAttribute('data-value-id', value.id);
                        valueItem.innerText = value.value;

                        // Add selection behavior
                        valueItem.addEventListener('click', function() {
                            const valueId = this.getAttribute('data-value-id');
                            const valueText = this.innerText;
                            const isSelected = this.classList.contains('selected');

                            // Toggle selection state
                            if (isSelected) {
                                // Remove selection
                                this.classList.remove('selected');
                                this.style.backgroundColor = '';
                                this.style.color = '';
                                this.style.display = ''; // Show this value again in the blue container

                                // Remove the chip
                                const chip = optionEl.querySelector(`.selected-value-chip[data-value-id="${valueId}"]`);
                                if (chip) chip.remove();

                                // Check if there are any selected values left
                                if (selectedValues.children.length === 0) {
                                    noValuesText.classList.remove('d-none');
                                    selectedValuesContainer.classList.add('d-none');
                                    valuesDisplay.classList.add('d-none');
                                }
                            } else {
                                // Add selection
                                this.classList.add('selected');
                                this.style.backgroundColor = '#4e73df';
                                this.style.color = 'white';
                                this.style.display = 'none'; // Hide this value from the blue container once selected

                                // Create and append chip
                                const chip = document.createElement('div');
                                chip.classList.add('selected-value-chip', 'd-flex', 'align-items-center');
                                chip.setAttribute('data-value-id', valueId);
                                chip.innerHTML = `
                                    <span>${valueText}</span>
                                    <button type="button" class="btn-close btn-sm remove-value" aria-label="Remove"></button>
                                `;
                                selectedValues.appendChild(chip);

                                // Show selected values container
                                selectedValuesContainer.classList.remove('d-none');
                                noValuesText.classList.add('d-none');
                                valuesDisplay.classList.remove('d-none');
                            }

                            // Check if ANY values are visible
                            const visibleValues = Array.from(valuesContainer.querySelectorAll('.variant-value-item')).filter(item =>
                                item.style.display !== 'none'
                            );

                            // Update container visibility based on visible values
                            if (visibleValues.length === 0) {
                                valuesContainer.classList.add('d-none');
                            } else {
                                valuesContainer.classList.remove('d-none');
                            }
                        });

                        valuesContainer.appendChild(valueItem);
                    });

                    // If no values found
                    if (attributeValues.length === 0) {
                        valuesContainer.innerHTML = '<div class="text-center p-2">No values available for this attribute</div>';
                    }
                });
            }

            // Function to update all attribute selects based on current selections
            function updateAttributeSelects() {
                document.querySelectorAll('.attribute-select').forEach(select => {
                    const currentValue = select.value;
                    const currentOptionEl = select.closest('.variant-option');

                    // Get all attribute options
                    const options = select.querySelectorAll('option');

                    // Reset all options visibility
                    options.forEach(option => {
                        const optionValue = option.value;
                        if (!optionValue) return; // Skip the empty "Select attribute" option

                        // If this attribute is selected elsewhere and not in this select, disable it
                        if (selectedAttributes.has(optionValue) && currentValue !== optionValue) {
                            option.disabled = true;
                            option.style.display = 'none';
                        } else {
                            option.disabled = false;
                            option.style.display = '';
                        }
                    });
                });
            }

            // Initialize existing attribute selects
            document.querySelectorAll('.attribute-select').forEach(select => {
                initAttributeSelect(select);

                // Set initial selected attribute if there's a value
                if (select.value) {
                    selectedAttributes.add(select.value);
                    select.setAttribute('data-previous-value', select.value);
                }
            });

            // Run initial update
            updateAttributeSelects();

            // Add a global function to handle a value being unselected
            function handleValueUnselect(optionEl, valueId) {
                const valuesContainer = optionEl.querySelector('.variant-values-list');
                const valueItem = optionEl.querySelector(`.variant-value-item[data-value-id="${valueId}"]`);

                if (valueItem) {
                    // Unselect the value item
                    valueItem.classList.remove('selected');
                    valueItem.style.backgroundColor = '';
                    valueItem.style.color = '';
                    valueItem.style.display = ''; // Show this value again
                }

                // Always show the values container when a value is unselected
                valuesContainer.classList.remove('d-none');

                // Force redisplay after a small delay to ensure it takes effect
                setTimeout(() => {
                    if (valuesContainer) {
                        valuesContainer.classList.remove('d-none');

                        // Make sure the unselected value is visible
                        if (valueItem) {
                            valueItem.style.display = '';
                        }
                    }
                }, 10);
            }

            // Handle user interactions
            document.addEventListener('click', function(e) {
                // Check if this is a click on the attribute select
                const select = e.target.closest('.attribute-select');
                if (select) {
                    const optionEl = select.closest('.variant-option');
                    const valuesContainer = optionEl.querySelector('.variant-values-list');

                    // Always show the values container when clicking on the dropdown
                    valuesContainer.classList.remove('d-none');

                    // Make sure any selected values stay hidden
                    const selectedItems = optionEl.querySelectorAll('.variant-value-item.selected');
                    selectedItems.forEach(item => {
                        item.style.display = 'none';
                    });
                }

                // Handle remove option links
                if (e.target.classList.contains('remove-option')) {
                    const optionEl = e.target.closest('.variant-option');
                    if (document.querySelectorAll('.variant-option').length > 1) {
                        // Remove this attribute from tracked selections before removing the element
                        const select = optionEl.querySelector('.attribute-select');
                        if (select && select.value) {
                            selectedAttributes.delete(select.value);
                        }

                        optionEl.remove();

                        // Update selects after removal
                        updateAttributeSelects();
                    }
                }

                // Handle removing selected value chips
                if (e.target.classList.contains('remove-value') || e.target.closest('.remove-value')) {
                    const chip = e.target.closest('.selected-value-chip');
                    const valueId = chip.getAttribute('data-value-id');
                    const optionEl = chip.closest('.variant-option');

                    // Remove the chip
                    chip.remove();

                    // Handle unselecting the value
                    handleValueUnselect(optionEl, valueId);

                    // Check if there are any selected values left
                    const selectedValuesContainer = optionEl.querySelector('.selected-values-container');
                    const selectedValues = selectedValuesContainer.querySelector('.selected-values');
                    const noValuesText = selectedValuesContainer.querySelector('.no-values-text');
                    const valuesDisplay = selectedValuesContainer.querySelector('.variant-values-display');

                    if (selectedValues.children.length === 0) {
                        noValuesText.classList.remove('d-none');
                        selectedValuesContainer.classList.add('d-none');
                        if (valuesDisplay) valuesDisplay.classList.add('d-none');
                    }
                }
            });

            // Add new option
            addOptionBtn.addEventListener('click', function() {
                optionCount++;

                // Create new option element
                const newOption = document.createElement('div');
                newOption.classList.add('variant-option', 'border-bottom', 'border-translucent', 'border-dashed', 'border-sm-0', 'border-bottom-xl', 'pb-4', 'mt-4');
                newOption.setAttribute('data-option', optionCount);

                // Create option HTML
                newOption.innerHTML = `
                    <div class="d-flex flex-wrap mb-2">
                        <h5 class="text-body-highlight me-2">Option ${optionCount}</h5>
                        <a class="fw-bold fs-9 remove-option" href="javascript:void(0)">Remove</a>
                    </div>
                    <select class="form-select mb-3 attribute-select">
                        <option value="">Select attribute</option>
                        @foreach ($attributes as $attribute)
                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                        @endforeach
                    </select>
                    <div class="variant-values-container border rounded">
                        <div class="variant-values-list p-2" id="variant-values-option-${optionCount}">
                            <!-- Values will be populated here dynamically -->
                        </div>
                        <div class="selected-values-container p-2 border-top d-none">
                            <div class="variant-values-display p-2 mb-2">
                                <div class="d-flex flex-wrap gap-2 selected-values"></div>
                            </div>
                            <div class="text-muted mt-2 no-values-text">No choices to choose from</div>
                        </div>
                    </div>
                `;

                // Append to container
                variantsContainer.appendChild(newOption);

                // Initialize the new select
                initAttributeSelect(newOption.querySelector('.attribute-select'));

                // Update attribute selects to reflect current selections
                updateAttributeSelects();
            });
        });
    </script>
@endsection
