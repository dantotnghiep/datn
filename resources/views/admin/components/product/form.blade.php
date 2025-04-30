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
                    @if (!isset($isReadOnly) || !$isReadOnly)
                        <button class="btn btn-primary mb-2 mb-sm-0" type="button" id="publish-btn" name="action"
                            value="publish" onclick="submitForm('publish')">{{ isset($item) ? 'Update' : 'Create new' }}</button>
                    @else
                        <button class="btn btn-primary mb-2 mb-sm-0" type="button" disabled title="This product has purchased variations and cannot be updated">Update</button>
                    @endif
                </div>
            </div>
            <div class="row g-5">
                <div class="col-12 col-xl-8">

                    <div class="row">
                        <div class="col-5">
                            <h4 class="mb-3">Title</h4>
                            <input class="form-control mb-5 @error('name') is-invalid @enderror" type="text"
                                name="name" placeholder="Write title here..." value="{{ $item->name ?? old('name') }}"
                                required />
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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

                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $item->name }}"
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

                    <!-- Generated Variations - Now in left column -->
                    <div class="mt-5 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Generated Variations</h4>
                            @if (!isset($isReadOnly) || !$isReadOnly)
                            <button type="button" class="btn btn-primary" id="generate-variations-btn">
                                Generate Variations
                            </button>
                            @else
                            <span class="badge bg-warning">Some variations have been purchased and can't be deleted</span>
                            @endif
                        </div>
                        <div id="generated-variations" class="mt-3">
                            <div class="alert alert-info mb-4">
                                @if (!isset($isReadOnly) || !$isReadOnly)
                                    Select attribute options above and click "Generate Variations" to create all possible
                                    combinations
                                @else
                                    View only mode: Some variations have been purchased and are locked for editing
                                @endif
                            </div>

                            <!-- Generated variations list will be inserted here -->
                            <div id="variations-table-container" style="display: none;">
                                <ul class="list-group" id="variations-list">
                                    <!-- Variations will be added here -->
                                </ul>
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
                                                    @foreach (\App\Models\Category::all() as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ (isset($item) && $item->getRawOriginal('category_id') == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <div id="variants-container">
                                        <div class="variant-option mb-4" data-option="1">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Option 1</h6>
                                                <a href="#" class="text-primary remove-option">Remove</a>
                                            </div>
                                            <div class="mb-3">
                                                <select class="form-select variant-attribute-select">
                                                    <option value="">Size</option>
                                                </select>
                                            </div>
                                            <div class="variant-values-container p-3 border rounded mb-3">
                                                <!-- Values will be loaded here -->
                                            </div>
                                        </div>

                                        <div class="variant-option mb-4" data-option="2">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Option 2</h6>
                                                <a href="#" class="text-primary remove-option">Remove</a>
                                            </div>
                                            <div class="mb-3">
                                                <select class="form-select variant-attribute-select">
                                                    <option value="">Size</option>
                                                </select>
                                            </div>
                                            <div class="variant-values-container p-3 border rounded mb-3">
                                                <!-- Values will be loaded here -->
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" class="btn btn-outline-primary" id="add-option-btn">
                                                Add another option
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hidden input to store variants data -->
            <input type="hidden" id="variants-data" name="variants"
                value="{{ isset($existingVariantsData) && !empty($existingVariantsData) ? json_encode($existingVariantsData) : '' }}">

            <!-- Hidden input to store generated variations data -->
            <input type="hidden" id="generated-variations-data" name="generated_variations"
                value="{{ isset($existingGeneratedVariations) && !empty($existingGeneratedVariations) ? json_encode($existingGeneratedVariations) : '[]' }}">

            <!-- Debug info -->
            @if (isset($existingVariantsData) && count($existingVariantsData) > 0)
                <script>
                    console.log('Existing variants data from PHP:', @json($existingVariantsData));
                </script>
            @else
                <script>
                    console.log('No existing variants data found');
                </script>
            @endif

            @if (isset($existingGeneratedVariations) && count($existingGeneratedVariations) > 0)
                <script>
                    console.log('Existing generated variations from PHP:', @json($existingGeneratedVariations));
                </script>
            @else
                <script>
                    console.log('No existing generated variations found');
                </script>
            @endif

            <!-- Debug Helper -->
            <div id="debug-panel"
                style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; z-index: 9999; display: none;">
                <button onclick="reloadPage()" class="btn btn-sm btn-danger">Reload Page</button>
                <button onclick="clearLocalStorage()" class="btn btn-sm btn-warning mx-2">Clear Storage</button>
                <button onclick="toggleDebug()" class="btn btn-sm btn-secondary">Close</button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log("DOM Content loaded");

                    // Variants Management
                    initVariantsSystem();

                    // Handle form submission
                    setupFormSubmission();

                    // Initialize dropzone
                    initDropzone();

                    // Initialize variations generator
                    initVariationsGenerator();

                    // Initialize flatpickr if available
                    if (typeof flatpickr !== 'undefined') {
                        flatpickr(".datetimepicker", {
                            dateFormat: "d/m/Y",
                            disableMobile: true
                        });
                    }
                    
                    // Initialize primary image badges if editing a product
                    document.querySelectorAll('.form-check-input[name="primary_image"]:checked').forEach(radio => {
                        updatePrimaryImageBadges(radio);
                    });
                    
                    // Show the variations container if we have existing variations
                    const existingVariationsData = document.getElementById('generated-variations-data').value;
                    if (existingVariationsData && existingVariationsData !== '[]') {
                        try {
                            const parsedData = JSON.parse(existingVariationsData);
                            if (parsedData && parsedData.length > 0) {
                                document.getElementById('variations-table-container').style.display = 'block';
                                document.querySelector('#generated-variations .alert').style.display = 'none';
                            }
                        } catch (e) {
                            console.error('Error parsing variations data:', e);
                        }
                    }

                    // If in readonly mode, disable controls
                    const isReadOnly = {{ isset($isReadOnly) && $isReadOnly ? 'true' : 'false' }};
                    if (isReadOnly) {
                        // Disable attribute selects
                        document.querySelectorAll('.variant-attribute-select').forEach(select => {
                            select.disabled = true;
                        });
                        
                        // Hide remove option buttons
                        document.querySelectorAll('.remove-option').forEach(btn => {
                            btn.style.display = 'none';
                        });
                        
                        // Hide add option button
                        const addOptionBtn = document.getElementById('add-option-btn');
                        if (addOptionBtn) {
                            addOptionBtn.style.display = 'none';
                        }
                        
                        // Show notice at top of page
                        const noticeDiv = document.createElement('div');
                        noticeDiv.className = 'alert alert-warning mb-4';
                        noticeDiv.innerHTML = '<strong>Attention!</strong> This product has variations that have been purchased. Some editing options are restricted.';
                        
                        const formStart = document.querySelector('form .row.g-3.flex-between-end');
                        if (formStart) {
                            formStart.insertAdjacentElement('beforebegin', noticeDiv);
                        }
                    }
                });

                // Initialize the variants system
                function initVariantsSystem() {
                    const variantsContainer = document.getElementById('variants-container');
                    const addOptionBtn = document.getElementById('add-option-btn');
                    const variantsDataInput = document.getElementById('variants-data');
                    
                    // Load attributes into selects
                    loadAttributesIntoSelects();
                    
                    // Handle existing variants data
                    loadExistingVariantsData();
                    
                    // Event listener for adding new option
                    addOptionBtn.addEventListener('click', function() {
                        addNewVariantOption();
                    });
                    
                    // Event delegation for remove option links
                    variantsContainer.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-option')) {
                            e.preventDefault();
                            const variantOption = e.target.closest('.variant-option');
                            if (variantOption) {
                                variantOption.remove();
                                updateVariantsData();
                            }
                        }
                    });
                    
                    // Event delegation for attribute select changes
                    variantsContainer.addEventListener('change', function(e) {
                        if (e.target.classList.contains('variant-attribute-select')) {
                            const select = e.target;
                            const option = select.closest('.variant-option');
                            const valuesContainer = option.querySelector('.variant-values-container');
                            
                            // Update all selects to respect the new selection
                            loadAttributesIntoSelects();
                            
                            // Load values for the selected attribute
                            const attributeId = select.value;
                            if (attributeId) {
                                loadAttributeValues(attributeId, valuesContainer);
                            } else {
                                valuesContainer.innerHTML = '';
                            }
                            
                            updateVariantsData();
                        }
                    });
                }
                
                // Load attribute values for a specific attribute
                function loadAttributeValues(attributeId, container) {
                    container.innerHTML = '';
                    
                    // Get values for the attribute
                    const values = [
                        @foreach ($attributeValues as $value)
                            { 
                                id: {{ $value->id }}, 
                                attribute_id: {{ $value->attribute_id }}, 
                                value: "{{ $value->value }}" 
                            },
                        @endforeach
                    ].filter(v => v.attribute_id == attributeId);
                    
                    if (values.length === 0) {
                        container.innerHTML = '<p class="text-muted mb-0">No values available for this attribute</p>';
                        return;
                    }
                    
                    // Create a well-formatted container for the buttons
                    const buttonsContainer = document.createElement('div');
                    buttonsContainer.className = 'd-flex flex-wrap';
                    
                    // Display values as selectable options with the design from the image
                    values.forEach(val => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-outline-primary m-1 value-option';
                        btn.dataset.valueId = val.id;
                        btn.textContent = val.value;
                        
                        // Add click event handler
                        btn.addEventListener('click', function() {
                            // Toggle selected class
                            this.classList.toggle('selected');
                            if (this.classList.contains('selected')) {
                                this.classList.remove('btn-outline-primary');
                                this.classList.add('btn-primary');
                            } else {
                                this.classList.remove('btn-primary');
                                this.classList.add('btn-outline-primary');
                            }
                            updateVariantsData();
                        });
                        
                        buttonsContainer.appendChild(btn);
                    });
                    
                    container.appendChild(buttonsContainer);
                }

                // Add a new variant option
                function addNewVariantOption() {
                    const variantsContainer = document.getElementById('variants-container');
                    const optionCount = variantsContainer.querySelectorAll('.variant-option').length + 1;

                    const newOption = document.createElement('div');
                    newOption.className = 'variant-option mb-4';
                    newOption.dataset.option = optionCount;

                    newOption.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Option ${optionCount}</h6>
                            <a href="#" class="text-primary remove-option">Remove</a>
                        </div>
                        <div class="mb-3">
                            <select class="form-select variant-attribute-select">
                                <option value="">Select attribute</option>
                            </select>
                        </div>
                        <div class="variant-values-container p-3 border rounded mb-3">
                            <!-- Values will be loaded here -->
                        </div>
                    `;

                    // Insert before the add button
                    const addButton = document.getElementById('add-option-btn').parentNode;
                    variantsContainer.insertBefore(newOption, addButton);

                    // Load available attributes (those not already selected)
                    loadAttributesIntoSelects();

                    // Return the new option
                    return newOption;
                }

                // Load existing variants data
                function loadExistingVariantsData() {
                    const variantsDataInput = document.getElementById('variants-data');
                    const variantsData = variantsDataInput.value;

                    if (variantsData && variantsData.trim() !== '') {
                        try {
                            const parsedData = JSON.parse(variantsData);

                            if (Array.isArray(parsedData) && parsedData.length > 0) {
                                console.log('Loading variant data:', parsedData);
                                
                                // Remove default options
                                const variantOptions = document.querySelectorAll('.variant-option');
                                variantOptions.forEach(option => option.remove());

                                // Add options from the data
                                parsedData.forEach((variant, index) => {
                                    // Create new option
                                    const newOption = addNewVariantOption();

                                    // Set the selected attribute in each select box
                                    const select = newOption.querySelector('.variant-attribute-select');
                                    select.value = variant.attribute_id;

                                    // Load attribute values for selected attribute
                                    const valuesContainer = newOption.querySelector('.variant-values-container');
                                    loadAttributeValues(variant.attribute_id, valuesContainer);

                                    // Mark selected values after a small delay to ensure DOM is updated
                                    setTimeout(() => {
                                        if (variant.values && variant.values.length > 0) {
                                            const valueIds = variant.values.map(v => v.id);

                                            const valueButtons = valuesContainer.querySelectorAll('.value-option');
                                            valueButtons.forEach(btn => {
                                                if (valueIds.includes(parseInt(btn.dataset.valueId))) {
                                                    btn.classList.add('selected');
                                                    if (!btn.classList.contains('btn-primary')) {
                                                        btn.classList.remove('btn-outline-primary');
                                                        btn.classList.add('btn-primary');
                                                    }
                                                }
                                            });
                                        }

                                        // Call loadAttributesIntoSelects to update all selects
                                        loadAttributesIntoSelects();
                                    }, 100);
                                });
                            }
                        } catch (error) {
                            console.error('Error parsing existing variants data:', error);
                        }
                    }
                }

                // Update variants data based on UI
                function updateVariantsData() {
                    const variantsDataInput = document.getElementById('variants-data');
                    const variantOptions = document.querySelectorAll('.variant-option');
                    const variantsData = [];

                    variantOptions.forEach((option, index) => {
                        const select = option.querySelector('.variant-attribute-select');
                        const attributeId = select.value;

                        if (attributeId) {
                            const attributeName = select.options[select.selectedIndex].text;
                            const selectedValues = option.querySelectorAll('.value-option.selected');

                            const values = [];
                            selectedValues.forEach(val => {
                                values.push({
                                    id: parseInt(val.dataset.valueId),
                                    value: val.textContent
                                });
                            });

                            variantsData.push({
                                attribute_id: attributeId,
                                attribute_name: attributeName,
                                values: values
                            });
                        }
                    });

                    variantsDataInput.value = JSON.stringify(variantsData);
                }

                // Setup form submission
                function setupFormSubmission() {
                    function submitForm(action) {
                        const form = document.getElementById('product-form');
                        
                        // Prevent multiple submissions
                        if (form.hasAttribute('data-submitting')) {
                            return;
                        }
                        
                        // Update variants data
                        updateVariantsData();
                        
                        // Get current variations data
                        const generatedVariationsData = document.getElementById('generated-variations-data');
                        const variationsData = document.getElementById('variations-list').querySelectorAll('li');
                        
                        // Ensure the data is properly JSON formatted
                        try {
                            let isValid = true;
                            if (generatedVariationsData.value) {
                                // Parse and validate data
                                const parsedData = JSON.parse(generatedVariationsData.value);
                                
                                // Make sure all required fields are there
                                parsedData.forEach((variation, index) => {
                                    if (!variation.id || !variation.combination) {
                                        isValid = false;
                                    }
                                    
                                    if (variation.combination) {
                                        variation.combination.forEach((attr, attrIndex) => {
                                            if (!attr.value_id) {
                                                attr.value_id = attr.id; // Try to fix it
                                                if (!attr.value_id) {
                                                    isValid = false;
                                                }
                                            }
                                        });
                                    }
                                });
                                
                                // Update the input with fixed data
                                generatedVariationsData.value = JSON.stringify(parsedData);
                                
                                if (!isValid) {
                                    const debugPanel = document.getElementById('debug-panel');
                                    debugPanel.style.display = 'block';
                                    showVariationsDebug();
                                    validateVariationsData();
                                    
                                    if (!confirm('Some variations data may be invalid. Do you want to continue anyway?')) {
                                        return;
                                    }
                                }
                            }
                        } catch (e) {
                            alert('Error validating variations data: ' + e.message);
                            return;
                        }
                        
                        // Mark as submitting
                        form.setAttribute('data-submitting', 'true');
                        
                        // Add hidden input for action
                        let actionInput = form.querySelector('input[name="action"]');
                        if (!actionInput) {
                            actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            form.appendChild(actionInput);
                        }
                        actionInput.value = action;
                        
                        // Submit the form directly
                        form.submit();
                    }

                    // Handle form submission
                    document.querySelector('form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        updateVariantsData();
                        this.submit();
                    });

                    // Expose submitForm to the global scope
                    window.submitForm = submitForm;
                }

                // Initialize dropzone
                function initDropzone() {
                    Dropzone.autoDiscover = false;
                    
                    let myDropzone = new Dropzone("#product-images-upload", {
                        url: "{{ isset($item) ? route('admin.products.update', $item->id) : route('admin.products.store') }}",
                        paramName: "images",
                        autoProcessQueue: false,
                        uploadMultiple: true,
                        parallelUploads: 5,
                        maxFiles: 10,
                        maxFilesize: 5, // MB
                        acceptedFiles: "image/*",
                        addRemoveLinks: true,
                        previewsContainer: "#current-images-container",
                        clickable: "#product-images-upload",
                        createImageThumbnails: true,
                        thumbnailWidth: 150,
                        thumbnailHeight: 150,
                        init: function() {
                            let submitButton = document.querySelector("#publish-btn");
                            let myDropzone = this;
                            let form = document.querySelector("#product-form");

                            // When files are added
                            this.on("addedfile", function(file) {
                                // Create a new div for the image card
                                let imageCard = document.createElement('div');
                                imageCard.className = 'col-auto';
                                
                                let cardInner = document.createElement('div');
                                cardInner.className = 'product-image-card';
                                
                                // Add remove button
                                let removeBtn = document.createElement('button');
                                removeBtn.type = 'button';
                                removeBtn.className = 'btn-remove-image';
                                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                                removeBtn.onclick = function() {
                                    myDropzone.removeFile(file);
                                };
                                
                                // Add primary image radio
                                let actionDiv = document.createElement('div');
                                actionDiv.className = 'image-actions';
                                actionDiv.innerHTML = `
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
                                
                                cardInner.appendChild(removeBtn);
                                cardInner.appendChild(file.previewElement);
                                cardInner.appendChild(actionDiv);
                                imageCard.appendChild(cardInner);
                                
                                document.querySelector("#current-images-container").appendChild(imageCard);
                            });

                            // When a file is removed
                            this.on("removedfile", function(file) {
                                let card = file.previewElement.closest('.col-auto');
                                if (card) {
                                    card.remove();
                                }
                            });

                            // Handle form submission
                            form.addEventListener("submit", function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                
                                // If there are files to upload
                                if (myDropzone.getQueuedFiles().length > 0) {
                                    myDropzone.processQueue();
                                } else {
                                    // If no new files, just submit the form
                                    form.submit();
                                }
                            });

                            // Handle the sending of the files
                            this.on("sending", function(file, xhr, formData) {
                                // Append all form data
                                let formElements = form.elements;
                                for (let i = 0; i < formElements.length; i++) {
                                    let element = formElements[i];
                                    if (element.type !== 'file') {
                                        formData.append(element.name, element.value);
                                    }
                                }
                                
                                // Add PUT method for update
                                if ("{{ isset($item) }}") {
                                    formData.append('_method', 'PUT');
                                }
                            });

                            // After all files are processed
                            this.on("successmultiple", function(files, response) {
                                // Redirect or show success message
                                window.location.href = "{{ route('admin.products.index') }}";
                            });

                            // Handle errors
                            this.on("error", function(file, errorMessage) {
                                console.error('Upload error:', errorMessage);
                                alert('Error uploading file: ' + errorMessage);
                            });

                            // Handle the submit button click
                            submitButton.addEventListener("click", function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                form.dispatchEvent(new Event('submit'));
                            });
                        }
                    });

                    return myDropzone;
                }

                function toggleImageRemoval(button, imageId) {
                    const card = button.closest('.product-image-card');
                    const input = card.querySelector('.remove-image-input');
                    
                    if (card.classList.contains('marked-for-removal')) {
                        card.classList.remove('marked-for-removal');
                        input.value = '';
                    } else {
                        card.classList.add('marked-for-removal');
                        input.value = imageId;
                    }
                }

                // Initialize variations generator
                function initVariationsGenerator() {
                    const generateBtn = document.getElementById('generate-variations-btn');
                    const generatedVariationsData = document.getElementById('generated-variations-data');

                    // Load existing generated variations if any
                    loadExistingGeneratedVariations();

                    // Add event listener for the generate button
                    generateBtn.addEventListener('click', function() {
                        generateAllVariations();
                    });

                    // Event delegation for variation actions
                    document.getElementById('variations-list').addEventListener('click', function(e) {
                        if (e.target.classList.contains('btn-remove-variation') || e.target.closest(
                            '.btn-remove-variation')) {
                            const button = e.target.classList.contains('btn-remove-variation') ?
                                e.target : e.target.closest('.btn-remove-variation');
                            const row = button.closest('li');
                            const variationId = row.dataset.variationId;

                            console.log("Removing variation with ID:", variationId);
                            removeVariation(variationId);
                        }
                    });
                }

                // Display generated variations in the list
                function displayGeneratedVariations(combinations) {
                    const list = document.getElementById('variations-list');
                    const tableContainer = document.getElementById('variations-table-container');
                    const alert = document.querySelector('#generated-variations .alert');
                    
                    // Show table, hide alert
                    tableContainer.style.display = 'block';
                    alert.style.display = 'none';
                    
                    // Get existing data from hidden input
                    let existingData = [];
                    try {
                        const generatedVariationsData = document.getElementById('generated-variations-data');
                        existingData = JSON.parse(generatedVariationsData.value || '[]');
                    } catch (error) {
                        existingData = [];
                    }
                    
                    // Store all existing variation IDs in a Set for quick lookup
                    const existingIds = new Set(existingData.map(item => item.id));
                    
                    // Only add new combinations that don't already exist
                    const newCombinations = combinations.filter(combo => !existingIds.has(combo.id));
                    
                    // Create updated set of variations (maintain existing ones, add new ones)
                    // Important: We're NOT replacing existing variations with ones from combinations
                    // We're only adding new ones that don't exist yet
                    const updatedVariations = [...existingData, ...newCombinations];
                    
                    // Clear the variations list in the UI
                    list.innerHTML = '';
                    
                    // Sort variations by ID to ensure consistent display order
                    updatedVariations.sort((a, b) => {
                        // Simple string comparison of IDs
                        return a.id.localeCompare(b.id);
                    });
                    
                    // Rebuild the visual list with all variations
                    updatedVariations.forEach((variation) => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item';
                        item.dataset.variationId = variation.id;
                        
                        // Format the variation name from the combination
                        const attributes = {};
                        variation.combination.forEach(attr => {
                            attributes[attr.attribute_name] = attr.value;
                        });
                        
                        let variationName = '';
                        Object.keys(attributes).forEach(attrName => {
                            if (variationName) variationName += ', ';
                            variationName += `${attrName}: ${attributes[attrName]}`;
                        });
                        
                        // Check if this variation has been purchased
                        const isPurchased = variation.is_purchased === true;
                        
                        // Generate a different HTML based on whether variation is purchased
                        if (isPurchased) {
                            item.classList.add('purchased-variation');
                            item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <span>${variationName}</span>
                                    <span class="badge bg-info">Purchased</span>
                                </div>
                            `;
                        } else {
                            item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <span>${variationName}</span>
                                    <button type="button" class="btn-remove-variation">
                                        ×
                                    </button>
                                </div>
                            `;
                        }
                        
                        list.appendChild(item);
                    });
                    
                    // Save updated variations data to hidden input
                    saveGeneratedVariations(updatedVariations);
                }
                
                // Load existing generated variations if any
                function loadExistingGeneratedVariations() {
                    const input = document.getElementById('generated-variations-data');
                    
                    if (input.value && input.value !== '[]') {
                        try {
                            const data = JSON.parse(input.value);
                            if (data && data.length > 0) {
                                displayGeneratedVariations(data);
                            }
                        } catch (error) {
                            // Error handling
                        }
                    }
                }

                // Save generated variations to hidden input (always called before form submission)
                function saveGeneratedVariations(variations) {
                    const input = document.getElementById('generated-variations-data');
                    
                    // Ensure we're saving a clean array of variations
                    const cleanVariations = variations.map(variation => {
                        // Only keep essential properties to avoid circular references
                        return {
                            id: variation.id,
                            combination: variation.combination.map(attr => ({
                                attribute_id: attr.attribute_id,
                                attribute_name: attr.attribute_name,
                                value_id: attr.value_id || attr.id, // Ensure value_id is available
                                value: attr.value
                            })),
                            variation_id: variation.variation_id, // Keep the actual variation ID if it exists
                            is_purchased: variation.is_purchased // Keep purchased status
                        };
                    });
                    
                    // Format the JSON properly
                    const jsonString = JSON.stringify(cleanVariations);
                    input.value = jsonString;
                    
                    return jsonString;
                }

                // Remove a variation from the list
                function removeVariation(variationId) {
                    // Remove the variant from DOM
                    const item = document.querySelector(`li[data-variation-id="${variationId}"]`);
                    if (item) {
                        item.remove();
                    }
                    
                    // Get current data from hidden input
                    const input = document.getElementById('generated-variations-data');
                    let currentVariations = [];
                    
                    try {
                        currentVariations = JSON.parse(input.value || '[]');
                        // Remove the specified variation
                        const updatedVariations = currentVariations.filter(variation => variation.id !== variationId);
                        
                        // Update the hidden input with the new array
                        input.value = JSON.stringify(updatedVariations);
                        
                        // If no variations left, show the alert and hide the table
                        if (updatedVariations.length === 0) {
                            document.getElementById('variations-table-container').style.display = 'none';
                            document.querySelector('#generated-variations .alert').style.display = 'block';
                        }
                    } catch (error) {
                        // Error handling
                    }
                }

                // Generate all variations
                function generateAllVariations() {
                    // Get all selected attribute options
                    const variantOptions = document.querySelectorAll('.variant-option');
                    const attributeSelections = [];
                    
                    variantOptions.forEach(option => {
                        const select = option.querySelector('.variant-attribute-select');
                        if (select.value) {
                            const attributeId = select.value;
                            const attributeName = select.options[select.selectedIndex].text;
                            const selectedValues = option.querySelectorAll('.value-option.selected');
                            
                            if (selectedValues.length > 0) {
                                const values = [];
                                selectedValues.forEach(val => {
                                    values.push({
                                        id: parseInt(val.dataset.valueId),
                                        value: val.textContent
                                    });
                                });
                                
                                attributeSelections.push({
                                    attribute_id: attributeId,
                                    attribute_name: attributeName,
                                    values: values
                                });
                            }
                        }
                    });
                    
                    if (attributeSelections.length === 0) {
                        alert('Please select at least one attribute and its values first');
                        return;
                    }
                    
                    // Generate combinations
                    const combinations = generateCombinations(attributeSelections);
                    
                    // Display combinations in the list
                    displayGeneratedVariations(combinations);
                }

                // Debug Helper Functions
                function toggleDebug() {
                    const panel = document.getElementById('debug-panel');
                    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                    
                    // Add extra debug info for variations
                    if (panel.style.display !== 'none') {
                        showVariationsDebug();
                    }
                }

                function showVariationsDebug() {
                    const debugPanel = document.getElementById('debug-panel');
                    
                    // Check if debug info already exists
                    if (debugPanel.querySelector('#variations-debug')) {
                        return;
                    }
                    
                    // Create debug info
                    const variationsDebug = document.createElement('div');
                    variationsDebug.id = 'variations-debug';
                    variationsDebug.className = 'mt-3 p-2 bg-dark text-white';
                    
                    // Get variations data
                    const generatedVariationsInput = document.getElementById('generated-variations-data');
                    const variantsDataInput = document.getElementById('variants-data');
                    
                    // Create content
                    let content = '<h6>Variations Debug</h6>';
                    
                    try {
                        const generatedData = JSON.parse(generatedVariationsInput.value || '[]');
                        content += `<p>Generated Variations Count: ${generatedData.length}</p>`;
                        content += `<pre style="max-height: 200px; overflow: auto; font-size: 11px;">${JSON.stringify(generatedData, null, 2)}</pre>`;
                    } catch (e) {
                        content += `<p>Error parsing generated variations: ${e.message}</p>`;
                    }
                    
                    try {
                        const variantsData = JSON.parse(variantsDataInput.value || '[]');
                        content += `<p>Variants Attributes Count: ${variantsData.length}</p>`;
                        content += `<pre style="max-height: 200px; overflow: auto; font-size: 11px;">${JSON.stringify(variantsData, null, 2)}</pre>`;
                    } catch (e) {
                        content += `<p>Error parsing variants data: ${e.message}</p>`;
                    }
                    
                    // Add button to validate data
                    content += `<button onclick="validateVariationsData()" class="btn btn-sm btn-info mt-2">Validate Variations</button>`;
                    
                    variationsDebug.innerHTML = content;
                    debugPanel.appendChild(variationsDebug);
                }

                function validateVariationsData() {
                    try {
                        const generatedVariationsInput = document.getElementById('generated-variations-data');
                        const generatedData = JSON.parse(generatedVariationsInput.value || '[]');
                        
                        // Check for required fields
                        let valid = true;
                        const issues = [];
                        
                        generatedData.forEach((variation, index) => {
                            if (!variation.id) {
                                issues.push(`Variation ${index} missing ID`);
                                valid = false;
                            }
                            
                            if (!variation.combination || !Array.isArray(variation.combination)) {
                                issues.push(`Variation ${index} missing combination array`);
                                valid = false;
                            } else {
                                variation.combination.forEach((attr, attrIndex) => {
                                    if (!attr.value_id && !attr.id) {
                                        issues.push(`Variation ${index}, attribute ${attrIndex} missing value_id and id`);
                                        valid = false;
                                    }
                                });
                            }
                        });
                        
                        // Update UI with results
                        const variationsDebug = document.getElementById('variations-debug');
                        if (variationsDebug) {
                            const validationResult = document.createElement('div');
                            validationResult.className = valid ? 'text-success' : 'text-danger';
                            validationResult.innerHTML = `<p>Validation result: ${valid ? 'VALID' : 'INVALID'}</p>`;
                            
                            if (!valid) {
                                validationResult.innerHTML += '<ul>' + issues.map(issue => `<li>${issue}</li>`).join('') + '</ul>';
                            }
                            
                            const existing = variationsDebug.querySelector('.validation-result');
                            if (existing) {
                                existing.remove();
                            }
                            
                            validationResult.className += ' validation-result mt-2';
                            variationsDebug.appendChild(validationResult);
                        }
                        
                        return valid;
                    } catch (e) {
                        console.error('Validation error:', e);
                        return false;
                    }
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

                // Add event listener for primary image selection
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('primary-image-radio')) {
                        // When a primary radio is clicked
                        updatePrimaryImageBadges(e.target);
                    }
                });

                // Update primary image badges
                function updatePrimaryImageBadges(selectedRadio) {
                    // Remove all existing primary badges
                    document.querySelectorAll('.primary-badge').forEach(badge => {
                        badge.remove();
                    });

                    // Add badge to the selected image card
                    const card = selectedRadio.closest('.product-image-card');
                    if (card) {
                        const primaryBadge = document.createElement('div');
                        primaryBadge.className = 'primary-badge';
                        primaryBadge.innerHTML = '<i class="fas fa-star"></i>';
                        card.appendChild(primaryBadge);
                    }
                }

                // Generate all possible combinations from selected attributes
                function generateCombinations(attributeSelections) {
                    // Start with an empty array to hold the combinations
                    let result = [[]];
                    
                    // For each attribute
                    for (let i = 0; i < attributeSelections.length; i++) {
                        const attribute = attributeSelections[i];
                        const values = attribute.values;
                        const tmpResult = [];
                        
                        // For each existing combination
                        for (let j = 0; j < result.length; j++) {
                            const combo = result[j];
                            
                            // For each value of this attribute
                            for (let k = 0; k < values.length; k++) {
                                const value = values[k];
                                
                                // Create a new combination with this value
                                const newCombo = combo.slice();
                                newCombo.push({
                                    attribute_id: attribute.attribute_id,
                                    attribute_name: attribute.attribute_name,
                                    value_id: value.id,
                                    value: value.value
                                });
                                
                                // Add to temp results
                                tmpResult.push(newCombo);
                            }
                        }
                        
                        // Replace result with the new combinations
                        result = tmpResult;
                    }
                    
                    // Now add unique IDs to each combination
                    result = result.map((combination, index) => {
                        const id = generateVariationId(combination);
                        return {
                            id: id,
                            combination: combination
                        };
                    });
                    
                    return result;
                }

                // Generate a unique ID for a variation based on its combination
                function generateVariationId(combination) {
                    return combination.map(item => `${item.attribute_id}-${item.value_id}`).join('_');
                }

                // Load attributes into select elements
                function loadAttributesIntoSelects() {
                    const attributeSelects = document.querySelectorAll('.variant-attribute-select');
                    
                    const attributes = [
                        @foreach ($attributes as $attribute)
                            {
                                id: {{ $attribute->id }},
                                name: "{{ $attribute->name }}"
                            },
                        @endforeach
                    ];
                    
                    // Get already selected attributes
                    const selectedAttributes = new Set();
                    attributeSelects.forEach(select => {
                        if (select.value) {
                            selectedAttributes.add(select.value);
                        }
                    });
                    
                    attributeSelects.forEach(select => {
                        // Store current value if exists
                        const currentValue = select.value;
                        
                        // Clear existing options
                        select.innerHTML = '<option value="">Select attribute</option>';
                        
                        // Add attribute options
                        attributes.forEach(attr => {
                            // Skip if this attribute is already selected in another dropdown
                            // except for the currently selected value in THIS dropdown
                            if (selectedAttributes.has(attr.id.toString()) && attr.id.toString() !== currentValue) {
                                return;
                            }
                            
                            const option = document.createElement('option');
                            option.value = attr.id;
                            option.textContent = attr.name;
                            select.appendChild(option);
                        });
                        
                        // Restore selected value
                        if (currentValue) {
                            select.value = currentValue;
                        }
                    });
                }
            </script>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Dropzone
            initDropzone();
            
            // Initialize variations display
            const existingVariationsData = document.getElementById('generated-variations-data').value;
            if (existingVariationsData && existingVariationsData !== '[]') {
                try {
                    const parsedData = JSON.parse(existingVariationsData);
                    if (parsedData && parsedData.length > 0) {
                        document.getElementById('variations-table-container').style.display = 'block';
                        document.querySelector('#generated-variations .alert').style.display = 'none';
                    }
                } catch (e) {
                    console.error('Error parsing variations data:', e);
                }
            }

            // If in readonly mode, disable controls
            const isReadOnly = {{ isset($isReadOnly) && $isReadOnly ? 'true' : 'false' }};
            if (isReadOnly) {
                // Disable attribute selects
                document.querySelectorAll('.variant-attribute-select').forEach(select => {
                    select.disabled = true;
                });
                
                // Hide remove option buttons
                document.querySelectorAll('.remove-option').forEach(btn => {
                    btn.style.display = 'none';
                });
                
                // Hide add option button
                const addOptionBtn = document.getElementById('add-option-btn');
                if (addOptionBtn) {
                    addOptionBtn.style.display = 'none';
                }
                
                // Show notice at top of page
                const noticeDiv = document.createElement('div');
                noticeDiv.className = 'alert alert-warning mb-4';
                noticeDiv.innerHTML = '<strong>Attention!</strong> This product has variations that have been purchased. Some editing options are restricted.';
                
                const formStart = document.querySelector('form .row.g-3.flex-between-end');
                if (formStart) {
                    formStart.insertAdjacentElement('beforebegin', noticeDiv);
                }
            }
        });
    </script>

    <style>
        /* Product image styles */
        .product-image-card {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
        }

        .product-image-card img.product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-image-card:hover img.product-image {
            transform: scale(1.05);
        }

        .btn-remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 25px;
            height: 25px;
            background-color: #ff0000;
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            font-size: 12px;
        }

        .btn-remove-image:hover {
            background-color: #cc0000;
        }

        .image-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            display: none;
        }

        .action-overlay {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 8px;
            color: white;
        }

        .product-image-card:hover .image-actions {
            display: block;
        }

        .primary-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: #ffc107;
            color: #000;
            padding: 3px 5px;
            border-radius: 3px;
            font-size: 12px;
        }

        .marked-for-removal {
            opacity: 0.5;
        }

        .marked-for-removal:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 0, 0, 0.3);
            z-index: 5;
        }

        /* Variations table styles */
        #variations-table-container {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        #variations-list {
            list-style: none;
            padding: 0;
        }

        #variations-list li {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 8px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 500;
            font-size: 0.95rem;
        }

        #variations-list li:hover {
            background-color: #f8f9fa;
        }

        .btn-remove-variation {
            width: 28px;
            height: 28px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            padding: 0;
            font-size: 20px;
            background-color: #ff0000;
            color: white;
            border: none;
            cursor: pointer;
            line-height: 1;
            font-weight: bold;
        }

        .btn-remove-variation:hover {
            background-color: #cc0000;
            transform: scale(1.05);
        }

        #generate-variations-btn {
            min-width: 160px;
        }

        /* Variant styles */
        .variant-option {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 1rem;
        }

        .variant-values-container {
            min-height: 50px;
            background-color: #f8f9fa;
        }

        .value-option {
            transition: all 0.2s ease;
            min-width: 40px !important;
            min-height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 4px !important;
            border-radius: 5px !important;
            font-weight: 500 !important;
            padding: 6px 12px !important;
        }

        .value-option.selected {
            background-color: #0d6efd !important;
            color: white !important;
        }

        /* Custom dropdown styling */
        .form-select {
            appearance: auto;
        }

        /* Purchased variation styles */
        .purchased-variation {
            background-color: #f8f9fa !important;
            border-left: 3px solid #0dcaf0 !important;
        }
    </style>
@endsection
