@extends('admin.master')

@section('title', isset($item) ? 'Edit Promotion' : 'Create Promotion')

@section('content')
<div class="content">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route($route.'.index') }}">Promotions</a></li>
            <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Create' }}</li>
        </ol>
    </nav>

    <div class="mb-8">
        <h2 class="mb-2">{{ isset($item) ? 'Edit Promotion' : 'Create Promotion' }}</h2>
        <h5 class="text-body-tertiary fw-semibold">{{ isset($item) ? 'Edit promotion data' : 'Create new promotion' }}</h5>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ isset($item) ? route($route.'.update', $item->id) : route($route.'.store') }}" method="POST">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    @foreach($fields as $field => $options)
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label fw-bold" for="{{ $field }}">
                                {{ $options['label'] ?? ucfirst($field) }}
                            </label>

                            @switch($options['type'] ?? 'text')
                                @case('textarea')
                                    <textarea
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        rows="4"
                                    >{{ old($field, isset($item) ? $item->$field : '') }}</textarea>
                                    @break

                                @case('select')
                                    <select
                                        class="form-select @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                    >
                                        @foreach($options['options'] ?? [] as $value => $label)
                                            <option value="{{ $value }}" {{ old($field, isset($item) ? $item->$field : '') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @break

                                @case('datetime')
                                    <input
                                        type="datetime-local"
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        value="{{ old($field, isset($item) ? $item->$field : '') }}"
                                    >
                                    @break

                                @case('boolean')
                                    <div class="form-check form-switch">
                                        <input
                                            type="checkbox"
                                            class="form-check-input @error($field) is-invalid @enderror"
                                            id="{{ $field }}"
                                            name="{{ $field }}"
                                            value="1"
                                            {{ old($field, isset($item) ? $item->$field : false) ? 'checked' : '' }}
                                        >
                                    </div>
                                    @break

                                @default
                                    <input
                                        type="{{ $options['type'] ?? 'text' }}"
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        value="{{ old($field, isset($item) ? $item->$field : '') }}"
                                        @if(isset($options['step'])) step="{{ $options['step'] }}" @endif
                                    >
                            @endswitch

                            @error($field)
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route($route.'.index') }}" class="btn btn-phoenix-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($item) ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle discount type change
        const discountTypeSelect = document.getElementById('discount_type');
        const discountValueInput = document.getElementById('discount_value');

        if (discountTypeSelect && discountValueInput) {
            discountTypeSelect.addEventListener('change', function() {
                if (this.value === 'percentage') {
                    discountValueInput.setAttribute('max', '100');
                } else {
                    discountValueInput.removeAttribute('max');
                }
            });

            // Trigger on load
            discountTypeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush

@endsection