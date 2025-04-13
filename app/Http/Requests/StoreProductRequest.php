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

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'selected_attributes' => 'nullable|array',
            'variations' => 'required|array',
            'variations.*.attribute_values' => 'required|array',
            'variations.*.sku' => 'required|string|unique:variations,sku',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.stock' => 'required|integer|min:0',
            'variations.*.sale_price' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm đã tồn tại. Vui lòng chọn tên khác.',
            'slug.required' => 'Slug là bắt buộc.',
            'slug.string' => 'Slug phải là chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại. Vui lòng chọn slug khác.',
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.exists' => 'Danh mục được chọn không tồn tại.',
            'main_image.required' => 'Hình ảnh chính là bắt buộc.',
            'main_image.image' => 'File phải là hình ảnh.',
            'main_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg.',
            'main_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'additional_images.*.image' => 'File phải là hình ảnh.',
            'additional_images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg.',
            'additional_images.*.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'variations.required' => 'Phải có ít nhất một biến thể sản phẩm.',
            'variations.array' => 'Biến thể phải là một mảng.',
            'variations.*.attribute_values.required' => 'Thuộc tính biến thể là bắt buộc.',
            'variations.*.attribute_values.array' => 'Thuộc tính biến thể phải là một mảng.',
            'variations.*.sku.required' => 'SKU là bắt buộc cho mỗi biến thể.',
            'variations.*.sku.unique' => 'SKU đã tồn tại. Vui lòng chọn SKU khác.',
            'variations.*.price.required' => 'Giá là bắt buộc cho mỗi biến thể.',
            'variations.*.price.numeric' => 'Giá phải là số.',
            'variations.*.price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'variations.*.stock.required' => 'Số lượng là bắt buộc cho mỗi biến thể.',
            'variations.*.stock.integer' => 'Số lượng phải là số nguyên.',
            'variations.*.stock.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
            'variations.*.sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'variations.*.sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
        ];
    }
}