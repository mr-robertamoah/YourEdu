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

    public function withValidator(Validator $validator)
    {
        if (request()->creator !== 'user') {
            
            $validator->addRules([
                'new_first_name' => 'required|string',
                'new_last_name' => 'required|string',
                'new_other_names' => 'nullable|string',
                'new_username' => 'required|alpha_num|unique:users,username|min:8',
                'new_email' => 'nullable|email|unique:users,email',
                'new_gender' => 'nullable|in:male,female',
                'new_dob' => 'nullable|string',
                'new_password' => 'required|alpha_num|min:8|confirmed',
            ]);

            if (json_decode(request()->parent)) {
                $validator->addRules([
                    'parent_first_name' => 'required|string',
                    'parent_last_name' => 'required|string',
                    'parent_other_names' => 'nullable|string',
                    'parent_username' => 'required|alpha_num|unique:users,username|min:8',
                    'parent_email' => 'nullable|email|unique:users,email',
                    'parent_gender' => 'nullable|in:male,female',
                    'parent_dob' => 'nullable|string',
                    'parent_password' => 'required|alpha_num|min:8|confirmed',
                ]);
            }
        }
    }
    //check dob for admin, facilitator, parent
    public function messages()
    {
        return [
            'custom' => [
                
            ]
        ];
    }
}
