<?php

namespace App\Http\Requests\UrlLibraries;

class EditNoteRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'note' => parent::rules()['note'],
        ];
    }
}
