<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateSettingRequest extends FormRequest
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
            // 'subject' => 'required|string',
            'body' => 'nullable|array',
            'body.*' => 'string|max:191',
            'type' => 'nullable|in:DEFAULT,amazing_deal,forget_password,new_arrival,overall_sale,sale,singup,winter_sale,contact_us,welcome,order',
        ];
    }
}
