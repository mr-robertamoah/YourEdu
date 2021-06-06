<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDiscussionRequest extends FormRequest
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
            'account' => 'required|string',
            'accountId' => 'required',
            'title' => 'required|string',
            'preamble' => 'nullable|string',
            'type' => 'required|in:public,private',
            'allowed' => 'required|in:all,learners,parents,facilitators,professionals,schools',
        ];
    }
}
