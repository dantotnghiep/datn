@extends('admin.master')



@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Create' }}</li>
            </ol>
        </nav>
        <form class="mb-9" method="POST"
            action="{{ isset($item) ? route('admin.products.update', $item->id) : route('admin.products.store') }}"
            enctype="multipart/form-data" id="product-form">
            @csrf
            @if (isset($item))
                @method('PUT')
            @endif
            <div class="row g-3 flex-between-end mb-5">
                <div class="col-auto">
                    <h2 class="mb-2">{{ isset($item) ? 'Edit product' : 'Add a product' }}</h2>
                    <h5 class="text-body-tertiary fw-semibold">Complete all fields</h5>
                </div>
                <div class="col-auto">
                    <a class="btn btn-phoenix-secondary me-2 mb-2 mb-sm-0"
                        href="{{ route('admin.products.index') }}">Cancel</a>
                    <button class="btn btn-phoenix-primary me-2 mb-2 mb-sm-0" type="button" name="action" value="draft"
                        onclick="submitForm('draft')">Save draft</button>
                    <button class="btn btn-primary mb-2 mb-sm-0" type="button" id="publish-btn" name="action"
                        value="publish" onclick="submitForm('publish')">Publish product</button>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-12 col-xl-8">

                    <div class="row">
                        <div class="col-5">
                            <h4 class="mb-3">Title</h4>
                            <input class="form-control mb-5" type="text" name="name" placeholder="Write title here..."
                                value="{{ $item->name ?? old('name') }}" required />

                        </div>
                        <div class="col-7">
                            <div class="mb-6">
                                <h4 class="mb-3">Description</h4>
                                <div class="description-container">
                                    <textarea class="tinymce form-control custom-editor" name="description"
                                        data-tinymce='{"height":"30rem","placeholder":"Write a description here...","skin":"oxide","content_css":"default","menubar":false,"statusbar":false,"toolbar":"bold italic underline | bullist numlist | link image | formatselect","plugins":"link image lists"}'>{{ $item->description ?? old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-3">Images</h4>
                        <div class="row g-3" id="current-images-container">
                            @if (isset($item) && $item->images->count() > 0)
                                @foreach ($item->images as $image)
                                    <div class="col-auto">
                                        <div class="product-image-card">
                                            <button type="button" class="btn-remove-image"
                                                onclick="toggleImageRemoval(this, {{ $image->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $item->name }}"
                                                class="product-image">

                                            <div class="image-actions">
                                                <div class="action-overlay">
                                                    <div class="d-flex flex-column gap-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="primary_image" value="{{ $image->id }}"
                                                                {{ $image->is_primary ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                <i class="fas fa-star me-1"></i> Set as Primary
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($image->is_primary)
                                                <div class="primary-badge">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            @endif

                                            <input type="hidden" name="remove_images[]" value=""
                                                class="remove-image-input">
                                            <input type="hidden" name="image_orders[{{ $image->id }}]"
                                                value="{{ $image->order }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="dropzone dropzone-multiple p-0 mt-3" id="product-images-upload">
                            <div class="fallback">
                                <input name="images[]" type="file" multiple />
                            </div>
                            <div class="dz-message text-body-tertiary text-opacity-85" data-dz-message="data-dz-message">
                                Drag your photos here<span class="text-body-secondary px-1">or</span>
                                <button class="btn btn-link p-0" type="button">Browse from device</button><br />
                                <img class="mt-3 me-2"
                                    src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/icons/image-icon.png') }}"
                                    width="40" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="row g-2">
                        <div class="col-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row gx-3">
                                        <div class="col-12 col-sm-6 col-xl-12">
                                            <div class="mb-4">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="mb-0 text-body-highlight me-2">Category</h5><a
                                                        class="fw-bold fs-9"
                                                        href="{{ route('admin.categories.create') }}">Add new category</a>
                                                </div>
                                                <select class="form-select mb-3" name="category_id" aria-label="category"
                                                    required>
                                                    <option value="">Select Category</option>
                                                    @foreach (\App\Models\Category::all() as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ (isset($item) && $item->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
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
                                                    <input class="form-check-input" id="is_hot" name="is_hot"
                                                        type="checkbox" value="1"
                                                        {{ (isset($item) && $item->is_hot) || old('is_hot') ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="is_hot">Mark as
                                                        featured</label>
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
                                    <div id="attributes-container">
                                        <div class="mb-4">
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                @foreach ($attributes as $attribute)
                                                    <button type="button"
                                                        class="btn attribute-btn {{ isset($selected_attribute) && $selected_attribute == $attribute->id ? 'btn-primary' : 'btn-outline-primary' }}"
                                                        data-attribute-id="{{ $attribute->id }}"
                                                        data-attribute-name="{{ $attribute->name }}">
                                                        {{ $attribute->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="variant-values-panel p-3 border rounded mb-4">
                                            <h6 class="mb-3">Select Values</h6>
                                            <div id="attribute-values-container" class="d-flex flex-wrap gap-2">
                                                <!-- Values will be loaded here -->
                                            </div>
                                        </div>

                                        <div id="selected-variants-container" class="mb-4">
                                            <h6 class="mb-3">Selected Variants</h6>
                                            <div class="selected-variants-list">
                                                <!-- Selected variants will appear here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hidden input to store variants data -->
            <input type="hidden" id="variants-data" name="variants" value="{{ isset($existingVariantsData) && !empty($existingVariantsData) ? json_encode($existingVariantsData) : '' }}">

            <!-- Debug info -->
            @if(isset($existingVariantsData) && count($existingVariantsData) > 0)
                <script>
                    console.log('Existing variants data from PHP:', @json($existingVariantsData));
                </script>
            @else
                <script>
                    console.log('No existing variants data found');
                </script>
            @endif

            <!-- Debug Helper -->
            <div id="debug-panel" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; z-index: 9999; display: none;">
                <button onclick="reloadPage()" class="btn btn-sm btn-danger">Reload Page</button>
                <button onclick="clearLocalStorage()" class="btn btn-sm btn-warning mx-2">Clear Storage</button>
                <button onclick="toggleDebug()" class="btn btn-sm btn-secondary">Close</button>
            </div>

            <script>
                // Debug Helper Functions
                function toggleDebug() {
                    const panel = document.getElementById('debug-panel');
                    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                }

                function reloadPage() {
                    window.location.reload();
                }

                function clearLocalStorage() {
                    localStorage.clear();
                    sessionStorage.clear();
                    alert('Local storage cleared!');
                }

                // Show debug panel with Ctrl+D
                document.addEventListener('keydown', function(e) {
                    if (e.ctrlKey && e.key === 'd') {
                        e.preventDefault();
                        toggleDebug();
                    }
                });
            </script>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM Content loaded - initializing variants...");

            // Simple Variant System - New Implementation
            const attributesContainer = document.getElementById('attributes-container');
            const attributeValuesContainer = document.getElementById('attribute-values-container');
            const selectedVariantsContainer = document.getElementById('selected-variants-container');
            const selectedVariantsList = document.querySelector('.selected-variants-list');
            const variantsDataInput = document.getElementById('variants-data');

            // Storage for selected variants data
            const selectedVariants = {};

            // Load existing variants if in edit mode
            function loadExistingVariants() {
                const variantsData = variantsDataInput.value;
                console.log('Raw variants data:', variantsData);

                if (variantsData && variantsData.trim() !== '') {
                    try {
                        const parsedData = JSON.parse(variantsData);
                        console.log('Parsed variants data:', parsedData);

                        if (Array.isArray(parsedData) && parsedData.length > 0) {
                            parsedData.forEach(variant => {
                                if (variant && variant.attribute_id && variant.values) {
                                    // Add to selected variants object
                                    selectedVariants[variant.attribute_id] = {
                                        attribute_id: parseInt(variant.attribute_id),
                                        attribute_name: variant.attribute_name,
                                        values: variant.values || []
                                    };

                                    // Immediately highlight the attribute button
                                    const attributeBtn = document.querySelector(`.attribute-btn[data-attribute-id="${variant.attribute_id}"]`);
                                    if (attributeBtn) {
                                        attributeBtn.classList.remove('btn-outline-primary');
                                        attributeBtn.classList.add('btn-primary');
                                    }
                                }
                            });

                            // Update display after loading data
                            updateSelectedVariantsDisplay();

                            // Update attribute button status
                            updateAttributeButtonsStatus();

                            console.log('Selected variants after loading:', selectedVariants);

                            // Auto-select the first attribute to display its values
                            setTimeout(() => {
                                // Show the selected variants in the selected-variants-list
                                updateSelectedVariantsDisplay();

                                // Click the first attribute to show its values
                                const firstAttributeId = Object.keys(selectedVariants)[0];
                                if (firstAttributeId) {
                                    const attributeBtn = document.querySelector(`.attribute-btn[data-attribute-id="${firstAttributeId}"]`);
                                    if (attributeBtn) {
                                        attributeBtn.click();
                                    }
                                }
                            }, 300);
                        }
                    } catch (error) {
                        console.error('Error parsing existing variants data:', error);
                    }
                }
            }

            // Event listener for attribute buttons
            attributesContainer.addEventListener('click', function(e) {
                const attributeBtn = e.target.closest('.attribute-btn');
                if (!attributeBtn) return;

                const attributeId = attributeBtn.dataset.attributeId;
                const attributeName = attributeBtn.dataset.attributeName;

                // Only highlight this button as active (for visual feedback)
                // without changing the highlighted status of other buttons
                attributeBtn.classList.remove('btn-outline-primary');
                attributeBtn.classList.add('btn-primary');

                // Load attribute values for this attribute
                loadAttributeValues(attributeId, attributeName);
            });

            // Load attribute values for a specific attribute
            function loadAttributeValues(attributeId, attributeName) {
                console.log(`Loading values for attribute ID ${attributeId} (${attributeName})`);

                // Clear previous values
                attributeValuesContainer.innerHTML = '';

                // Keep track of values we add
                const addedValues = new Set();

                // Add attribute values as buttons
                @foreach ($attributeValues as $value)
                    if ({{ $value->attribute_id }} == attributeId) {
                        const valueBtn = document.createElement('button');
                        valueBtn.type = 'button';
                        valueBtn.className = 'btn btn-outline-secondary rounded-pill value-btn';
                        valueBtn.dataset.valueId = {{ $value->id }};
                        valueBtn.dataset.value = "{{ $value->value }}";
                        valueBtn.dataset.attributeId = attributeId;
                        valueBtn.dataset.attributeName = attributeName;
                        valueBtn.textContent = "{{ $value->value }}";

                        // Keep track of which values we've added
                        addedValues.add({{ $value->id }});

                        // Check if this value is already selected
                        if (selectedVariants[attributeId] &&
                            selectedVariants[attributeId].values.some(v => v.id == {{ $value->id }})) {
                            valueBtn.classList.remove('btn-outline-secondary');
                            valueBtn.classList.add('btn-secondary');
                        }

                        attributeValuesContainer.appendChild(valueBtn);
                    }
                @endforeach

                // Log what values were found and added
                console.log(`Added ${addedValues.size} values for attribute ID ${attributeId}`);

                // If no values, show message
                if (attributeValuesContainer.children.length === 0) {
                    attributeValuesContainer.innerHTML =
                        '<p class="text-muted">No values available for this attribute</p>';
                }
            }

            // Handle value selection
            attributeValuesContainer.addEventListener('click', function(e) {
                const valueBtn = e.target.closest('.value-btn');
                if (!valueBtn) return;

                const attributeId = valueBtn.dataset.attributeId;
                const attributeName = valueBtn.dataset.attributeName;
                const valueId = parseInt(valueBtn.dataset.valueId);
                const value = valueBtn.dataset.value;

                // Toggle selection
                if (valueBtn.classList.contains('btn-secondary')) {
                    // Deselect
                    valueBtn.classList.remove('btn-secondary');
                    valueBtn.classList.add('btn-outline-secondary');

                    // Remove from selected variants
                    if (selectedVariants[attributeId]) {
                        selectedVariants[attributeId].values = selectedVariants[attributeId].values.filter(
                            v => v.id !== valueId);

                        // If no values left, remove the attribute
                        if (selectedVariants[attributeId].values.length === 0) {
                            delete selectedVariants[attributeId];
                        }
                    }
                } else {
                    // Select
                    valueBtn.classList.remove('btn-outline-secondary');
                    valueBtn.classList.add('btn-secondary');

                    // Add to selected variants
                    if (!selectedVariants[attributeId]) {
                        selectedVariants[attributeId] = {
                            attribute_id: parseInt(attributeId),
                            attribute_name: attributeName,
                            values: []
                        };
                    }

                    selectedVariants[attributeId].values.push({
                        id: valueId,
                        value: value
                    });
                }

                // Update display and hidden input
                updateSelectedVariantsDisplay();
            });

            // Update the display of selected variants
            function updateSelectedVariantsDisplay() {
                selectedVariantsList.innerHTML = '';

                // Convert the object to array for the backend
                const variantsArray = Object.values(selectedVariants);

                // Set the hidden input value
                variantsDataInput.value = JSON.stringify(variantsArray);

                // Show selected variants UI
                if (variantsArray.length === 0) {
                    selectedVariantsList.innerHTML = '<p class="text-muted">No variants selected</p>';
                    return;
                }

                // Create a card for each attribute with its values
                variantsArray.forEach(variant => {
                    const variantCard = document.createElement('div');
                    variantCard.className = 'card mb-3';

                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body';

                    // Attribute name heading
                    const heading = document.createElement('h6');
                    heading.className = 'card-title';
                    heading.textContent = variant.attribute_name;

                    // Value chips
                    const valuesContainer = document.createElement('div');
                    valuesContainer.className = 'd-flex flex-wrap gap-2 mt-2';

                    variant.values.forEach(val => {
                        const chip = document.createElement('div');
                        chip.className = 'badge bg-primary rounded-pill';
                        chip.textContent = val.value;
                        valuesContainer.appendChild(chip);
                    });

                    cardBody.appendChild(heading);
                    cardBody.appendChild(valuesContainer);
                    variantCard.appendChild(cardBody);
                    selectedVariantsList.appendChild(variantCard);
                });

                // Update attribute buttons status
                updateAttributeButtonsStatus();
            }

            // Update attribute buttons to show selection count
            function updateAttributeButtonsStatus() {
                // First, remove all existing selection indicators
                document.querySelectorAll('.attr-selection-count').forEach(el => el.remove());

                // Then add new indicators
                Object.keys(selectedVariants).forEach(attributeId => {
                    const values = selectedVariants[attributeId].values;
                    if (values && values.length > 0) {
                        const attributeBtn = document.querySelector(`.attribute-btn[data-attribute-id="${attributeId}"]`);
                        if (attributeBtn) {
                            // Ensure the button is marked as selected
                            attributeBtn.classList.remove('btn-outline-primary');
                            attributeBtn.classList.add('btn-primary');

                            // Add count indicator if more than 0 values
                            if (values.length > 0) {
                                // Check if counter already exists
                                if (!attributeBtn.querySelector('.attr-selection-count')) {
                                    const counter = document.createElement('span');
                                    counter.className = 'attr-selection-count';
                                    counter.textContent = values.length;
                                    attributeBtn.appendChild(counter);
                                } else {
                                    attributeBtn.querySelector('.attr-selection-count').textContent = values.length;
                                }
                            }
                        }
                    }
                });
            }

            // Function to handle form submission
            function submitForm(action) {
                const form = document.getElementById('product-form');

                // Prevent multiple submissions
                if (form.hasAttribute('data-submitting')) {
                    return;
                }
                form.setAttribute('data-submitting', 'true');

                // Add action to form data
                const formData = new FormData(form);
                formData.append('action', action);

                // Make sure variants data is up to date
                const variantsArray = Object.values(selectedVariants);
                variantsDataInput.value = JSON.stringify(variantsArray);

                // Add Dropzone files if any
                if (window.productDropzone && window.productDropzone.getQueuedFiles) {
                    const queuedFiles = window.productDropzone.getQueuedFiles();
                    queuedFiles.forEach(function(file) {
                        formData.append('images[]', file);
                    });
                }

                let method = form.getAttribute('method').toUpperCase();
                let url = form.getAttribute('action');

                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

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
                        form.removeAttribute('data-submitting');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error saving product. Please try again.');
                    form.removeAttribute('data-submitting');
                });
            }

            // Handle form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Make sure variants data is up to date
                const variantsArray = Object.values(selectedVariants);
                variantsDataInput.value = JSON.stringify(variantsArray);

                this.submit();
            });

            // Expose submitForm to the global scope
            window.submitForm = submitForm;

            // Initialize with existing values if available
            loadExistingVariants();

            // Initialize dropzone
            if (typeof Dropzone !== 'undefined') {
                Dropzone.autoDiscover = false;

                window.productDropzone = new Dropzone("#product-images-upload", {
                    url: "{{ isset($item) ? route('admin.products.update', $item->id) : route('admin.products.store') }}",
                    paramName: "images",
                    acceptedFiles: "image/*",
                    addRemoveLinks: false,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 10,
                    maxFiles: 10,
                    maxFilesize: null,
                    previewsContainer: false,
                    clickable: "#product-images-upload",
                    init: function() {
                        let myDropzone = this;
                        const currentImagesContainer = document.getElementById(
                            'current-images-container');

                        this.on("addedfile", function(file) {
                            // Create preview element
                            const col = document.createElement('div');
                            col.className = 'col-auto';

                            const card = document.createElement('div');
                            card.className = 'product-image-card';

                            // Create remove button
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn-remove-image';
                            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                            removeBtn.onclick = function() {
                                myDropzone.removeFile(file);
                                col.remove();
                            };

                            // Create image element
                            const img = document.createElement('img');
                            img.className = 'product-image';

                            // Create primary radio
                            const imageActions = document.createElement('div');
                            imageActions.className = 'image-actions';
                            imageActions.innerHTML = `
                                <div class="action-overlay">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="primary_image" value="new_${file.name}">
                                            <label class="form-check-label">
                                                <i class="fas fa-star me-1"></i> Set as Primary
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // Read and set image preview
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                img.src = e.target.result;
                            };
                            reader.readAsDataURL(file);

                            // Assemble the preview
                            card.appendChild(removeBtn);
                            card.appendChild(img);
                            card.appendChild(imageActions);
                            col.appendChild(card);

                            // Add to current images container
                            currentImagesContainer.appendChild(col);
                        });
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
        });
    </script>

    <style>
        #image-preview-container .dz-image {
            position: relative !important;
        }

        #image-preview-container .dz-remove {
            position: absolute !important;
            top: 6px !important;
            right: 6px !important;
            background: #ff4444 !important;
            color: #fff !important;
            border-radius: 50% !important;
            width: 22px !important;
            height: 22px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 18px !important;
            z-index: 20 !important;
            text-decoration: none !important;
            border: none !important;
            cursor: pointer !important;
            padding: 0 !important;
            font-weight: bold !important;
        }

        #image-preview-container .dz-remove:before {
            content: "×";
            font-size: 22px;
            font-weight: bold;
            line-height: 1;
            color: #fff;
            display: block;
        }

        #image-preview-container .dz-remove:hover {
            background: #cc0000 !important;
        }

        .product-image-card {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .product-image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .product-image-card:hover .product-image {
            transform: scale(1.1);
        }

        .image-actions {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0);
            transition: all 0.3s ease;
        }

        .product-image-card:hover .image-actions {
            background: rgba(0, 0, 0, 0.7);
        }

        .action-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            transform: translateY(100%);
            transition: all 0.3s ease;
        }

        .product-image-card:hover .action-overlay {
            transform: translateY(0);
        }

        .form-check-label {
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .form-check-input {
            cursor: pointer;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .primary-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #0d6efd;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .primary-badge i {
            filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.3));
        }

        .btn-remove-image {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
            transition: all 0.2s ease;
            padding: 0;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-remove-image:hover {
            background: #dc3545;
            color: white;
            transform: scale(1.1);
        }

        .product-image-card.marked-for-removal {
            opacity: 0.5;
            filter: grayscale(100%);
        }

        .product-image-card.marked-for-removal .btn-remove-image {
            background: #dc3545;
            color: white;
        }

        /* New styles for the variant selection */
        .variant-values-panel {
            background-color: #f8f9fa;
        }

        .value-btn {
            transition: all 0.2s ease;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .value-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
        }

        .attribute-btn {
            font-weight: 600;
            min-width: 100px;
            position: relative;
        }

        .attribute-btn.btn-primary {
            position: relative;
        }

        .attribute-btn.btn-primary::after {
            content: "";
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #198754;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-primary, .btn-secondary {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .selected-variants-list .card {
            transition: all 0.3s ease;
            border-left: 4px solid #0d6efd;
        }

        .selected-variants-list .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .badge.bg-primary {
            font-size: 0.9rem;
            padding: 6px 12px;
        }

        .attr-selection-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #198754;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            border: 1px solid white;
        }
    </style>

    <script>
        function toggleImageRemoval(button, imageId) {
            const card = button.closest('.product-image-card');
            const removeInput = card.querySelector('.remove-image-input');

            if (card.classList.contains('marked-for-removal')) {
                // Unmark for removal
                card.classList.remove('marked-for-removal');
                removeInput.value = '';
            } else {
                // Mark for removal
                card.classList.add('marked-for-removal');
                removeInput.value = imageId.toString(); // Ensure imageId is converted to string
            }
        }
    </script>
