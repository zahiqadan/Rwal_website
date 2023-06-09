<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:191',
            'direction' => 'nullable|in:ltr,rtl',
            'code' => 'nullable|string|max:191',
            'directory' => 'nullable|string|max:191',
            'is_default' => 'nullable|in:0,1',
        ];
    }
}
