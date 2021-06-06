<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'username'=> 'required|alpha_dash|min:8|max:100|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string',
            'passwordConfirmation'=>'required|string',
            'firstName' => 'nullable|string',
            'lastName' => 'nullable|string',
            'otherNames' => 'nullable|string',
            'dob' => 'nullable|date',
        ];
    }
}
