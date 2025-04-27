@extends('admin.master')

@section('title', isset($item) ? 'Edit Product' : 'Create Product')

@push('styles')
    <link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/choices/choices.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dropzone/dropzone.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <style>
        /* Custom styles for Choices.js to make it match the design in image 2 */
        .choices {
            margin-bottom: 1rem;
        }

        .choices__inner {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            min-height: 44px;
            padding: 0.375rem 0.75rem;
        }

        .choices__list--multiple .choices__item {
            background-color: #f5f5f5;
            border: 1px solid #e0e0e0;
            color: #333;
            border-radius: 0.25rem;
            margin-right: 5px;
            padding: 2px 8px;
        }

        .choices__list--dropdown {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .choices__list--dropdown .choices__item--selectable {
            padding: 6px 10px;
        }

        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #0d6efd;
            color: white;
        }

        .choices__input {
            background-color: transparent;
            margin-bottom: 0;
        }

        .choices[data-type*=select-multiple] .choices__button {
            border-left: 1px solid rgba(0, 0, 0, 0.1);
            padding-left: 8px;
            margin-left: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route($route . '.index') }}">{{ ucfirst(str_replace('admin.', '', $route)) }}</a></li>
                <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Add' }} a product</li>
            </ol>
        </nav>

        <form action="{{ isset($item) ? route($route . '.update', $item->id) : route($route . '.store') }}" method="POST"
            enctype="multipart/form-data" class="mb-9">
            @csrf
            @if (isset($item))
                @method('PUT')
            @endif

            <div class="row g-3 flex-between-end mb-5">
                <div class="col-auto">
                    <h2 class="mb-2">{{ isset($item) ? 'Edit Product' : 'Add a product' }}</h2>
                    <h5 class="text-body-tertiary fw-semibold">Orders placed across your store</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route($route . '.index') }}" class="btn btn-phoenix-secondary me-2 mb-2 mb-sm-0">Discard</a>
                    <button class="btn btn-primary mb-2 mb-sm-0"
                        type="submit">{{ isset($item) ? 'Save Product' : 'Publish product' }}</button>
                </div>
            </div>

            <div class="row g-5">
                <div class="col-12 col-xl-8">
                    <!-- Product Title -->
                    <h4 class="mb-3">Product Title</h4>
                    <input class="form-control mb-5 @error('name') is-invalid @enderror" type="text" name="name"
                        id="name" placeholder="Write title here..."
                        value="{{ old('name', isset($item) ? $item->name : '') }}" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <!-- Product Description -->
                    <div class="mb-6">
                        <h4 class="mb-3">Product Description</h4>
                        <textarea class="tinymce @error('description') is-invalid @enderror" name="description" id="description"
                            data-tinymce='{"height":"15rem","placeholder":"Write a description here..."}'>{{ old('description', isset($item) ? $item->description : '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Display Images -->
                    <h4 class="mb-3">Display images</h4>
                    <div class="dropzone dropzone-multiple p-0 mb-5" id="my-awesome-dropzone" data-dropzone="data-dropzone">
                        <div class="fallback">
                            <input name="images[]" type="file" multiple="multiple" accept="image/*"
                                class="@error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" />
                        </div>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- Existing Images -->
                        @if (isset($item) && $item->images->count() > 0)
                            <div class="dz-preview d-flex flex-wrap">
                                @foreach ($item->images as $image)
                                    <div class="border border-translucent bg-body-emphasis rounded-3 d-flex flex-center position-relative me-2 mb-2"
                                        style="height:80px;width:80px;" id="image-container-{{ $image->id }}">
                                        <img class="dz-image" src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="Product Image" />
                                        <div class="position-absolute top-0 end-0">
                                            <div class="form-check mb-0 mt-1 me-1">
                                                <input class="form-check-input" type="radio"
                                                    name="existing_images[{{ $image->id }}][is_primary]" value="1"
                                                    {{ $image->is_primary ? 'checked' : '' }}
                                                    id="primary-{{ $image->id }}">
                                            </div>
                                        </div>
                                        <input type="hidden" name="existing_images[{{ $image->id }}][order]"
                                            value="{{ $image->order }}">
                                        <a class="dz-remove text-body-quaternary" href="javascript:void(0)"
                                            data-image-id="{{ $image->id }}"><span data-feather="x"></span></a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="dz-message text-body-tertiary text-opacity-85" data-dz-message="data-dz-message">
                            Drag your photo here<span class="text-body-secondary px-1">or</span><button
                                class="btn btn-link p-0" type="button">Browse from device</button><br />
                            <img class="mt-3 me-2" src="../../../assets/img/icons/image-icon.png" width="40"
                                alt="" />
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-12 col-xl-4">
                    <div class="row g-2">
                        <!-- Organization Card -->
                        <div class="col-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Organize</h4>
                                    <div class="row gx-3">
                                        <div class="col-12 col-sm-6 col-xl-12">
                                            <div class="mb-4">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="mb-0 text-body-highlight me-2">Category</h5>
                                                </div>
                                                <select class="form-select mb-3 @error('category_id') is-invalid @enderror"
                                                    name="category_id" id="category_id">
                                                    <option value="">Select Category</option>
                                                    @foreach ($fields['category_id']['options'] as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('category_id', isset($item) ? $item->category_id : '') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Variants Card -->
                        <div class="col-12 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Variants</h4>
                                    <div class="row g-3">
                                        <div class="col-12 col-xl-12">
                                            <div class="border-bottom border-translucent border-dashed pb-4 mb-4">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="text-body-highlight me-2">Option 1 <a
                                                            class="fw-bold fs-9 text-primary ms-2"
                                                            href="javascript:void(0)" id="remove-option-1">Remove</a></h5>
                                                </div>
                                                <select class="form-select mb-3" name="option_type[0]"
                                                    id="option-type-1">
                                                    <option value="">Select attribute type</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="product-variant-select-menu">
                                                    <select class="form-select choices-multiple mb-3"
                                                        name="option_values[0][]" id="option-values-1"
                                                        data-choices="data-choices" multiple="multiple"
                                                        data-options='{"removeItemButton":true,"placeholder":true}'>
                                                        <option value="">Select values</option>
                                                        <!-- Values will be populated via JS when attribute type is selected -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-xl-12" id="option-2-container" style="display:none;">
                                            <div class="border-bottom border-translucent border-dashed pb-4 mb-4">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="text-body-highlight me-2">Option 2 <a
                                                            class="fw-bold fs-9 text-primary ms-2"
                                                            href="javascript:void(0)" id="remove-option-2">Remove</a></h5>
                                                </div>
                                                <select class="form-select mb-3" name="option_type[1]"
                                                    id="option-type-2">
                                                    <option value="">Select attribute type</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="product-variant-select-menu">
                                                    <select class="form-select choices-multiple mb-3"
                                                        name="option_values[1][]" id="option-values-2"
                                                        data-choices="data-choices" multiple="multiple"
                                                        data-options='{"removeItemButton":true,"placeholder":true}'>
                                                        <option value="">Select values</option>
                                                        <!-- Values will be populated via JS when attribute type is selected -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-xl-12" id="option-3-container" style="display:none;">
                                            <div class="border-bottom border-translucent border-dashed pb-4 mb-4">
                                                <div class="d-flex flex-wrap mb-2">
                                                    <h5 class="text-body-highlight me-2">Option 3 <a
                                                            class="fw-bold fs-9 text-primary ms-2"
                                                            href="javascript:void(0)" id="remove-option-3">Remove</a></h5>
                                                </div>
                                                <select class="form-select mb-3" name="option_type[2]"
                                                    id="option-type-3">
                                                    <option value="">Select attribute type</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="product-variant-select-menu">
                                                    <select class="form-select choices-multiple mb-3"
                                                        name="option_values[2][]" id="option-values-3"
                                                        data-choices="data-choices" multiple="multiple"
                                                        data-options='{"removeItemButton":true,"placeholder":true}'>
                                                        <option value="">Select values</option>
                                                        <!-- Values will be populated via JS when attribute type is selected -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-phoenix-primary w-100 mb-4" type="button"
                                        id="add-option-btn">Generate Variations</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- Scripts for Product Form -->
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/choices/choices.min.js') }}"></script>
    <script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TinyMCE
            if (window.tinymce) {
                let tinymceOptions = {
                    selector: '.tinymce',
                    height: 400,
                    menubar: false,
                    plugins: 'link image lists table media',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | table media | removeformat',
                };
                tinymce.init(tinymceOptions);
            }

            // Initialize Dropzone
            if (window.Dropzone) {
                new Dropzone("#my-awesome-dropzone", {
                    url: '/file/upload', // Replace with your actual upload endpoint
                    addRemoveLinks: true,
                    thumbnailWidth: 80,
                    thumbnailHeight: 80
                });
            }

            // Store attribute data for JS use
            const attributesData = {
                @foreach ($attributes as $attribute)
                {{ $attribute->id }}: {
                    id: {{ $attribute->id }},
                    name: '{{ $attribute->name }}',
                    values: [
                        @foreach ($attribute->values as $value)
                        {
                            id: {{ $value->id }},
                            value: '{{ $value->value }}'
                        },
                        @endforeach
                    ]
                },
                @endforeach
            };
            
            console.log('Available attributes:', attributesData);
            
            // Function to update value options when attribute type changes
            function setupAttributeSelects() {
                $('#option-type-1, #option-type-2, #option-type-3').on('change', function() {
                    const selectId = $(this).attr('id');
                    const optionNum = selectId.split('-')[2];
                    const valuesSelectId = `option-values-${optionNum}`;
                    const attributeId = $(this).val();
                    
                    console.log(`Attribute type changed for option ${optionNum} to ${attributeId}`);
                    
                    // Clear current options
                    $(`#${valuesSelectId}`).empty();
                    $(`#${valuesSelectId}`).append('<option value="">Select values</option>');
                    
                    // Add new options based on selected attribute
                    if (attributeId && attributesData[attributeId]) {
                        const attribute = attributesData[attributeId];
                        console.log(`Loading ${attribute.values.length} values for ${attribute.name}`);
                        
                        attribute.values.forEach(value => {
                            $(`#${valuesSelectId}`).append(
                                `<option value="${value.id}">${value.value}</option>`
                            );
                        });
                        
                        // Reinitialize Choices.js for this select
                        if (window.Choices) {
                            if ($(`#${valuesSelectId}`)[0].choices) {
                                $(`#${valuesSelectId}`)[0].choices.destroy();
                            }
                            
                            new Choices($(`#${valuesSelectId}`)[0], {
                                removeItemButton: true,
                                placeholder: true,
                                placeholderValue: 'Select values',
                                itemSelectText: '',
                            });
                        }
                    } else {
                        console.log('No attribute selected or attribute data not found');
                    }
                });
                
                // Trigger change for any pre-selected attributes (in edit mode)
                $('#option-type-1, #option-type-2, #option-type-3').each(function() {
                    if ($(this).val()) {
                        $(this).trigger('change');
                    }
                });
            }
            
            // Setup attribute selects once DOM is ready
            setupAttributeSelects();
            
            // Initialize Choices.js for selects that need it
            if (window.Choices) {
                document.querySelectorAll('[data-choices]').forEach(select => {
                    // Don't initialize option-values selects here - they'll be initialized when their type is selected
                    if (!select.id.startsWith('option-values-')) {
                        new Choices(select, {
                            removeItemButton: true,
                            placeholder: true,
                            placeholderValue: 'Select...',
                            itemSelectText: '',
                        });
                    }
                });
            }
            
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
            
            // Handle adding/removing options
            let visibleOptions = 1;
            
            // Add option button
            $('#add-option-btn').on('click', function() {
                if (visibleOptions < 3) {
                    visibleOptions++;
                    $(`#option-${visibleOptions}-container`).show();
                    
                    if (visibleOptions === 3) {
                        $(this).prop('disabled', true);
                    }
                }
            });
            
            // Remove option buttons
            $('[id^="remove-option-"]').on('click', function() {
                const optionNum = $(this).attr('id').split('-')[2];
                
                if (optionNum == 1 && visibleOptions > 1) {
                    // If removing first option but others exist, just clear its values
                    $('#option-type-1').val('').trigger('change');
                } else if (optionNum < visibleOptions) {
                    // If removing middle option, shift later options up
                    for (let i = optionNum; i < visibleOptions; i++) {
                        const nextTypeValue = $(`#option-type-${parseInt(i)+1}`).val();
                        $(`#option-type-${i}`).val(nextTypeValue).trigger('change');
                    }
                    
                    // Hide the last visible option
                    $(`#option-${visibleOptions}-container`).hide();
                    $(`#option-type-${visibleOptions}`).val('');
                    visibleOptions--;
                    $('#add-option-btn').prop('disabled', false);
                } else if (optionNum == visibleOptions) {
                    // If removing the last visible option
                    $(`#option-${optionNum}-container`).hide();
                    $(`#option-type-${optionNum}`).val('');
                    visibleOptions--;
                    $('#add-option-btn').prop('disabled', false);
                }
            });
            
            // Handle image removal
            $(document).on('click', '.dz-remove', function() {
                const imageId = $(this).data('image-id');
                
                if (imageId) {
                    $(`#image-container-${imageId}`).remove();
                    
                    // Add hidden input to mark this image for deletion
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'remove_images[]',
                        value: imageId
                    }).appendTo('form');
                }
            });
        });
    </script>
@endpush
