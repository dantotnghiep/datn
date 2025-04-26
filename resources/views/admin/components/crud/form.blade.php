@extends('admin.master')

@section('title', isset($item) ? 'Edit' : 'Create New')

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
        <h2 class="mb-2">{{ isset($item) ? 'Edit' : 'Create' }}</h2>
        <h5 class="text-body-tertiary fw-semibold">{{ isset($item) ? 'Edit data' : 'Create data' }}</h5>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($item) ? route($route.'.update', $item->id) : route($route.'.store') }}" method="POST" enctype="multipart/form-data">
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

                                @case('file')
                                    <input
                                        type="file"
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                    >
                                    @if(isset($item) && $item->$field)
                                        <div class="mt-2">
                                            <p class="fs-9 text-body-tertiary">Current file: {{ $item->$field }}</p>
                                        </div>
                                    @endif
                                    @break

                                @default
                                    <input
                                        type="{{ $options['type'] ?? 'text' }}"
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        value="{{ old($field, isset($item) ? $item->$field : '') }}"
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
@endsection 