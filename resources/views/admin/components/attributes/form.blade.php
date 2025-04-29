@extends('admin.master')

@section('title', isset($item) ? 'Sửa thuộc tính' : 'attributes')

@section('content')
<div class="content">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route($route.'.index') }}">Thuộc tính</a></li>
            <li class="breadcrumb-item active">{{ isset($item) ? 'Sửa' : 'create' }}</li>
        </ol>
    </nav>

    <div class="mb-8">
        <h2 class="mb-2">{{ isset($item) ? 'Sửa thuộc tính' : 'Attributes' }}</h2>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ isset($item) ? route($route.'.update', $item->id) : route($route.'.store') }}" method="POST">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold" for="name">Name Attribute</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $item->name ?? '') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Attribute Value</label>
                        <div id="attribute-values" class="mb-3">
                            @if(isset($item) && $item->values->count() > 0)
                                @foreach($item->values as $value)
                                    <div class="input-group mb-2">
                                        <input type="text" name="values[]" class="form-control"
                                               value="{{ $value->value }}" required>
                                        <button type="button" class="btn btn-danger remove-value">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" name="values[]" class="form-control" required>
                                    <button type="button" class="btn btn-danger remove-value">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-success" id="add-value">
                            <i class="fas fa-plus"></i> Add Value
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">

                    <button type="submit" class="btn btn-primary">
                        {{ isset($item) ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('attribute-values');
    const addButton = document.getElementById('add-value');

    // Thêm giá trị mới
    addButton.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="values[]" class="form-control" required>
            <button type="button" class="btn btn-danger remove-value">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // Xóa giá trị
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-value')) {
            const row = e.target.closest('.input-group');
            if (container.children.length > 1) {
                row.remove();
            }
        }
    });
});
</script>
@endpush
