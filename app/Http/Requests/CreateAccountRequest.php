<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateAccountRequest extends FormRequest
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
            'create' => 'string',
            'creator' => 'string',
            'creatorId' => 'nullable|integer',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'role' => 'nullable|string',
            'other_name' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
    
    public function messages()
    {
        return [
            'custom' => [
                
            ]
        ];
    }
}
