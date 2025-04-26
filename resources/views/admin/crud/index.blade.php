@extends('admin.layouts.app')

@section('title', $title ?? 'List')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-3">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">{{ $title ?? 'List' }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route($route.'.index', ['trashed' => request()->get('trashed') ? 0 : 1]) }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ request()->get('trashed') ? 'View Active' : 'View Trash' }}
                </a>
                <a href="{{ route($route.'.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New
                </a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6">
            <form action="{{ route($route.'.index') }}" method="GET" class="space-y-4">
                <input type="hidden" name="trashed" value="{{ request()->get('trashed', 0) }}">

                <!-- Search Bar -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request()->get('search') }}"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Search...">
                        </div>
                    </div>

                    <!-- Filters Dropdown -->
                    <div class="flex space-x-4">
                        @foreach($fields as $field => $options)
                            @if(isset($options['filterable']) && $options['filterable'])
                            <select name="filter[{{ $field }}]"
                                    class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Filter {{ $options['label'] ?? ucfirst($field) }}</option>
                                @foreach($options['filter_options'] ?? [] as $value => $label)
                                    <option value="{{ $value }}" {{ request()->input("filter.$field") == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @endif
                        @endforeach

                        <!-- Sort Dropdown -->
                        <select name="sort"
                                class="block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Sort By</option>
                            @foreach($fields as $field => $options)
                                @if(!isset($options['sortable']) || $options['sortable'])
                                    <option value="{{ $field }}_asc" {{ request()->get('sort') == $field.'_asc' ? 'selected' : '' }}>
                                        {{ $options['label'] ?? ucfirst($field) }} (A-Z)
                                    </option>
                                    <option value="{{ $field }}_desc" {{ request()->get('sort') == $field.'_desc' ? 'selected' : '' }}>
                                        {{ $options['label'] ?? ucfirst($field) }} (Z-A)
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Apply Filters Button -->
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Apply Filters
                    </button>

                    <!-- Clear Filters -->
                    @if(request()->has('search') || request()->has('filter') || request()->has('sort'))
                        <a href="{{ route($route.'.index', ['trashed' => request()->get('trashed', 0)]) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($fields as $field => $options)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $options['label'] ?? ucfirst($field) }}
                        </th>
                        @endforeach
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr>
                        @foreach($fields as $field => $options)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->$field }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($item->trashed())
                                <form action="{{ route($route.'.restore', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-50 transition-colors duration-200" title="Restore">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route($route.'.edit', $item->id) }}"
                                       class="p-1 rounded-full text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 transition-colors duration-200"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route($route.'.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1 rounded-full text-red-600 hover:text-red-900 hover:bg-red-50 transition-colors duration-200"
                                                title="Move to Trash"
                                                onclick="return confirm('Are you sure you want to move this item to trash?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
