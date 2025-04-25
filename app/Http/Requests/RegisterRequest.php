<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }
    public function messages(): array
    {
        return [
            '*.required' => ':attribute không được để trống.',
            '*.string' => ':attribute phải là chuỗi ký tự.',
            '*.max' => ':attribute không được vượt quá :max ký tự.',
            '*.min' => ':attribute phải có ít nhất :min ký tự.',
            '*.unique' => ':attribute đã được sử dụng.',
            '*.email' => ':attribute không đúng định dạng.',
            '*.confirmed' => ':attribute xác nhận không khớp.',
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'Họ tên',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'phone' => 'Số điện thoại',
            'password_confirmation' => 'Xác nhận mật khẩu',
        ];
    }



}