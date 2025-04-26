@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Thêm sản phẩm</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a></li>
                        <li>Thêm sản phẩm</li>
                    </ul>
                </div>
            </div>

<<<<<<< HEAD
            @if($errors->any())
                <div style="background-color: #000; color: #fff; padding: 15px; margin-bottom: 20px;">
                    <p>Gỡ lỗi: Tìm thấy {{ count($errors->all()) }} lỗi</p>
                    @foreach($errors->all() as $error)
                        <p>- {{ $error }}</p>
                    @endforeach
                </div>
            @endif

=======
>>>>>>> 7873a8d61dfe17657eabee634e0f46c7364efe7c
            @if ($errors->any())
                <div id="error-container" class="alert alert-info">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
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
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" name="name" class="form-control" id="slug"
                                            onkeyup="ChangeToSlug();"  value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="slug">Đường dẫn (Slug)</label>
                                        <input type="text" name="slug" class="form-control" id="convert_slug"
                                             value="{{ old('slug') }}">
                                    </div>
                                    <div class="col-md-6">
<<<<<<< HEAD
                                        <label for="category_id">Danh mục</label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">-- Chọn danh mục --</option>
=======
                                        <label for="category_id">Category</label>
                                        <select name="category_id" id="category_id" class="form-control" >
                                            <option value="">-- Select Category --</option>
>>>>>>> 7873a8d61dfe17657eabee634e0f46c7364efe7c
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
<<<<<<< HEAD
                                        <label for="status">Trạng thái</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
=======
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control" >
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
>>>>>>> 7873a8d61dfe17657eabee634e0f46c7364efe7c
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="description">Mô tả</label>
                                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    </div>

                                    <hr class="my-4">
                                    <h4>Hình ảnh</h4>

                                    <div class="col-md-6">

                                        <label for="main_image">Main Image</label>
                                        <input type="file" name="main_image" class="form-control" accept="image/*">

                                        <div class="mt-2" id="main_image_preview"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="additional_images">Hình ảnh phụ</label>
                                        <input type="file" name="additional_images[]" class="form-control" accept="image/*" multiple>
                                        <div class="mt-2" id="additional_images_preview"></div>
                                    </div>

                                    <hr class="my-4">
                                    <h4>Thuộc tính</h4>

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
                                    <h4>Biến thể</h4>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary mb-3" id="generate-variations">Tạo biến thể</button>
                                        <div id="variations-container">
                                            <!-- Các biến thể sẽ được thêm vào đây -->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
@endsection
=======

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview functionality
            const mainImageInput = document.querySelector('input[name="main_image"]');
            const additionalImagesInput = document.querySelector('input[name="additional_images[]"]');
            const mainImagePreview = document.getElementById('main_image_preview');
            const additionalImagesPreview = document.getElementById('additional_images_preview');

            // Thêm xử lý slug tự động từ tên sản phẩm
            const nameInput = document.getElementById('slug');
            const slugInput = document.getElementById('convert_slug');
            
            if (nameInput && slugInput) {
                // Hàm chuyển đổi tên thành slug
                function ChangeToSlug() {
                    let title = nameInput.value;
                    let slug = title.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    slugInput.value = slug;
                }
                
                // Gán hàm vào sự kiện input để cập nhật slug khi đang nhập tên
                nameInput.addEventListener('input', ChangeToSlug);
                
                // Gán hàm vào window để sử dụng với onkeyup đã có trong HTML
                window.ChangeToSlug = ChangeToSlug;
            }

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
                                <input type='text' name='variations[${index}][sku]' class='form-control' >
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Stock</label>
                                <input type='number' name='variations[${index}][stock]' class='form-control'  min='0'>
                            </div>
                            <div class='col-md-6 mb-2'>
                                <label>Price</label>
                                <input type='number' name='variations[${index}][price]' class='form-control' step='0.01'  min='0'>
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
            if (document.getElementById('error-container')) {
                setTimeout(function() {
                    const errorContainer = document.getElementById('error-container');
                    const yOffset = -100; // Adjust offset to better display
                    const y = errorContainer.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({top: y, behavior: 'smooth'});
                }, 300);
            }
            
            // Setup form submit to save scroll position
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    sessionStorage.setItem('scrollPosition', window.pageYOffset);
                });
            }
            
            // Restore scroll position if there are errors
            if ({{ $errors->any() ? 'true' : 'false' }}) {
                setTimeout(function() {
                    const errorContainer = document.getElementById('error-container');
                    if (errorContainer) {
                        const yOffset = -100;
                        const y = errorContainer.getBoundingClientRect().top + window.pageYOffset + yOffset;
                        window.scrollTo({top: y, behavior: 'smooth'});
                    } else {
                        const savedPosition = sessionStorage.getItem('scrollPosition');
                        if (savedPosition) {
                            window.scrollTo(0, parseInt(savedPosition));
                            sessionStorage.removeItem('scrollPosition');
                        }
                    }
                }, 300);
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
                
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }

        #error-container {
            font-size: 15px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 6px;
            background-color:rgba(255, 109, 109, 0.67);
            border: 1px solid #dee2e6;
            color: #333;
        }
        
        #error-container div {
            margin-bottom: 5px;
        }
    </style>

@endsection
>>>>>>> 7873a8d61dfe17657eabee634e0f46c7364efe7c
