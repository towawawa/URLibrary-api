<?php

namespace App\Http\Requests\Genres;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB制限
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'ジャンル名は必須です。',
            'name.max' => 'ジャンル名は255文字以内で入力してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像ファイルのサイズは5MB以下にしてください。',
        ];
    }
}
