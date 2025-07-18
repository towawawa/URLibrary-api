<?php

namespace App\Http\Requests\UrlLibraries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Request extends FormRequest
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
            'title' => ['required', 'max:255'],
            'url' => ['required', 'max:255', 'url'],
            'genreId' => ['nullable', 'integer', Rule::exists('genres', 'id')->where('user_id', Auth::id())],
            'hashTagIds' => ['nullable', 'array'],
            'hashTagIds.*' => ['integer', 'distinct', Rule::exists('hash_tags', 'id')->where('user_id', Auth::id())],
            'hashTagNames' => ['nullable', 'array'],
            'hashTagNames.*' => ['string', 'max:50', 'distinct'],
            'note' => ['nullable'],
        ];
    }
}
