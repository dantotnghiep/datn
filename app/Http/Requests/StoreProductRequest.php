<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Thay đổi nếu bạn cần kiểm tra quyền truy cập
    }

    public function rules()
    {
        $productId = $this->route('product'); // Assuming you're passing the product ID in the route

        // dd($this->all());
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'slug' => 'required|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'selected_attributes' => 'array', // Assuming this is an array of attribute IDs
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variations' => 'required|array',
            'variations.*.attribute_values' => 'required|array',
            'variations.*.sku' => 'required|string|unique:variations,sku',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.stock' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'name.unique' => 'The name must be unique. If you are trying to keep the old name, please ensure it is not changed.',
            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a string.',
            'slug.max' => 'The slug may not be greater than 255 characters.',
            'slug.unique' => 'The slug must be unique.',
            'variations.*.attribute_values.required' => 'The attribute values field is required.',
            'variations.*.attribute_values.array' => 'The attribute values field must be an array.',
            'variations.*.attribute_values.min' => 'The attribute values field must contain at least one item.',
            'variations.*.sku.required' => 'The SKU field is required.',
            'variations.*.sku.unique' => 'The SKU must be unique.',
            'variations.*.price.required' => 'The price field is required.',
            'variations.*.price.numeric' => 'The price must be a number.',
            'variations.*.price.min' => 'The price must be greater than 0.',
        ];
    }
}