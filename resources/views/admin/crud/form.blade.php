@extends('admin.layouts.app')

@section('title', isset($item) ? 'Edit' : 'Create New')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-3">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            {{ isset($item) ? 'Edit' : 'Create New' }}
        </h2>

        <form action="{{ isset($item) ? route($route.'.update', $item->id) : route($route.'.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            @foreach($fields as $field => $options)
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="{{ $field }}">
                        {{ $options['label'] ?? ucfirst($field) }}
                    </label>

                    @switch($options['type'] ?? 'text')
                        @case('textarea')
                            <textarea
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                rows="4"
                            >{{ old($field, isset($item) ? $item->$field : '') }}</textarea>
                            @break

                        @case('select')
                            <select
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
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
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="{{ $field }}"
                                name="{{ $field }}"
                            >
                            @if(isset($item) && $item->$field)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current file: {{ $item->$field }}</p>
                                </div>
                            @endif
                            @break

                        @default
                            <input
                                type="{{ $options['type'] ?? 'text' }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                value="{{ old($field, isset($item) ? $item->$field : '') }}"
                            >
                    @endswitch

                    @error($field)
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ isset($item) ? 'Update' : 'Create' }}
                </button>
                <a href="{{ route($route.'.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
