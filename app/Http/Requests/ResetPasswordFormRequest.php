<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ':attribute là bắt buộc.',
            '*.string' => ':attribute phải là chuỗi.',
            '*.min' => ':attribute phải có ít nhất :min ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'Mật khẩu mới',
        ];
    }
}
