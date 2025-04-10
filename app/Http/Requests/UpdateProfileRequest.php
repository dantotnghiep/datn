<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            // 'address' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */

} 