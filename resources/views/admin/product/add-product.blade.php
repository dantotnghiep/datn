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


        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                            <div class="row cr-product-uploads">

                                <div class="col-lg-4 mb-991">
                                    <div class="cr-vendor-img-upload">
                                        <label for="main_image">Main Image</label>
                                        <input type="file" id="main_image" name="main_image" class="form-control" accept=".png, .jpg, .jpeg" required >
                                    </div>
                                </div>


                                <div class="col-lg-8">
                                    <label for="additional_images">Additional Images</label>
                                    <input type="file" id="additional_images" name="additional_images[]" class="form-control" accept=".png, .jpg, .jpeg" multiple>
                                </div>
                            </div>


                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <label for="name">Product Name</label>
                                    <input type="text" name="name" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" class="form-control" id="slug" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="price">Price (USD)</label>
                                    <input type="number" name="price" class="form-control" id="price" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sale_price">Sale Price (USD)</label>
                                    <input type="number" name="sale_price" class="form-control" id="sale_price">
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" id="quantity" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" require></textarea>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <label for="sale_start">Sale start</label>
                                        <input type="datetime-local" name="sale_start" id="sale_start" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sale_end">Sale end</label>
                                        <input type="datetime-local" name="sale_end" id="sale_end" class="form-control">
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status">Status</label>
                                    <div class="col-12">
                                        <select id="status" name="status" class="form-control">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="mt-4">
                                <h4>Attributes</h4>
                                <div id="attributes">
                                    <div class="attribute-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="attribute_0">Choose Attribute</label>
                                                <select name="attributes[0][attribute_id]" class="form-control select-attribute" data-index="0" id="attribute_0" required>
                                                    <option value="">-- Select Attribute --</option>
                                                    @foreach ($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 attribute-values-container" id="attribute-values-container-0">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-attribute" class="btn btn-secondary mt-3">Add Attribute</button>
                            </div>


                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                            <script>
                                const attributes = @json($attributes);
                                let attributeIndex = 1;


                                document.getElementById('add-attribute').addEventListener('click', function() {
                                    const attributeHtml = `
                                            <div class="attribute-item mt-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="attribute_${attributeIndex}">Choose Attribute</label>
                                                        <select name="attributes[${attributeIndex}][attribute_id]" class="form-control select-attribute" data-index="${attributeIndex}" id="attribute_${attributeIndex}" required>
                                                            <option value="">-- Select Attribute --</option>
                                                            ${attributes.map(attr => `<option value="${attr.id}">${attr.name}</option>`).join('')}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 attribute-values-container" id="attribute-values-container-${attributeIndex}">
                                                        <!-- Attribute values with delete buttons will appear here -->
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    document.getElementById('attributes').insertAdjacentHTML('beforeend', attributeHtml);
                                    attributeIndex++;
                                });


                                document.getElementById('attributes').addEventListener('change', function(e) {
                                    if (e.target.classList.contains('select-attribute')) {
                                        const index = e.target.getAttribute('data-index');
                                        const container = document.getElementById(`attribute-values-container-${index}`);
                                        const attributeId = e.target.value;


                                        container.innerHTML = '';


                                        const selectedAttribute = attributes.find(attr => attr.id == attributeId);

                                        if (selectedAttribute) {
                                            if (selectedAttribute.values && selectedAttribute.values.length > 0) {

                                                const valueList = selectedAttribute.values.map(value => `
                                                        <div class="attribute-value-item d-flex align-items-center">
                                                            <input type="hidden" name="attributes[${index}][value_ids][]" value="${value.id}">
                                                            <span class="mr-2">${value.value}</span>
                                                            <button type="button" class="btn btn-sm btn-danger remove-value" data-value-id="${value.id}" data-index="${index}">X</button>
                                                        </div>
                                                    `).join('');
                                                container.innerHTML = `<label>Attribute Values:</label><div>${valueList}</div>`;
                                            } else {

                                                container.innerHTML = `
                                                        <label>Attribute Values:</label>
                                                        <div>
                                                            <span>No attribute values available.</span>
                                                        </div>
                                                    `;
                                            }
                                        }
                                    }
                                });


                                document.getElementById('attributes').addEventListener('click', function(e) {
                                    if (e.target.classList.contains('remove-value')) {
                                        const valueId = e.target.getAttribute('data-value-id');
                                        const index = e.target.getAttribute('data-index');


                                        const valueItem = e.target.closest('.attribute-value-item');
                                        valueItem.remove();


                                        console.log(`Removed value ID: ${valueId} from attribute index: ${index}`);
                                    }
                                });
                            </script>
                            @endsection