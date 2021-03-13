<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreatePostRequest extends FormRequest
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
            'content' => 'nullable|string',
            'account' => 'required',
            'accountId' => 'required',
            'file.*' => 'nullable|file',
        ];
    }

    public function withValidator(Validator $validator)
    {
        if (request()->input('type') === 'book') {
            $validator->addRules([
                'title' => 'required|string',
                'authorNames' => 'nullable|string',
                'about' => 'nullable|string',
                'published' => 'nullable|date',
                'typeFiles.*' => 'nullable|file',
            ]);
        } else if (request()->input('type') === 'riddle') {
            $validator->addRules([
                'body' => 'required|string',
                'scoreOver' => 'nullable',
                'authorNames' => 'nullable|string',
                'published' => 'nullable|date',
                'typeFiles.*' => 'nullable|file',
            ]);
        } else if (request()->input('type') === 'poem') {
            $validator->addRules([
                'title' => 'required|string',
                'authorNames' => 'nullable|string',
                'about' => 'nullable|string',
                'sections' => 'nullable|string',
                'published' => 'nullable|date',
                'typeFiles.*' => 'nullable|file',
            ]);
        } else if (request()->input('type') === 'activity') {
            $validator->addRules([
                'description' => 'nullable|string',
                'published' => 'nullable|date',
                'typeFiles.*' => 'required|file',
            ]);
        } else if (request()->input('type') === 'question') {
            $validator->addRules([
                'body' => 'required|string',
                'scoreOver' => 'nullable',
                'possibleAnswers' => 'nullable|string',
                'published' => 'nullable|date',
                'typeFiles.*' => 'nullable|file',
            ]);
        } else if (request()->input('type') === 'lesson') {
            $validator->addRules([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'ageGroup' => 'nullable|string',
                'published' => 'nullable|date',
                'typeFiles.*' => 'nullable|file',
            ]);
        }
    }
}
