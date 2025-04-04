<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng gửi request này
    }

    public function rules(): array
{
    $id = $this->route('id'); // Lấy ID từ route

    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
        'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($id)],
        'password' => ['nullable', 'min:8'], // Không bắt buộc khi cập nhật
        'status' => ['required', Rule::in(['active', 'inactive'])],
        'role' => ['required', Rule::in(['user', 'admin', 'staff'])],
    ];
}
}
