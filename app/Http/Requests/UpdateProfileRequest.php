<?php

namespace App\Http\Requests;

use Carbon\Carbon;
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

        // Tính ngày cách đây 10 năm từ ngày hiện tại
        $tenYearsAgo = Carbon::now()->subYears(10)->format('Y-m-d');
        
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20,'. auth()->id(),
            'gender' => 'nullable|in:male,female,other',
            'birthday' => [
                'nullable',
                'date',
                'before:' . $tenYearsAgo, // Ngày sinh phải trước ngày cách đây 10 năm
            ],
            'avatar' => 'nullable|image|mimes:jpeg,png|max:1024', // Giới hạn 1MB, chỉ chấp nhận JPEG/PNG
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'birthday.before' => 'Bạn phải từ 10 tuổi trở lên.',
            'avatar.image' => 'Ảnh đại diện phải là file ảnh.',
            'avatar.mimes' => 'Ảnh đại diện chỉ hỗ trợ định dạng JPEG hoặc PNG.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 1MB.',

        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */

} 