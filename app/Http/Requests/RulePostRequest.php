<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RulePostRequest extends FormRequest
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
            'client_id' => 'required',
            'user_id' => 'required',
            'form_id' => 'required',
            'question_id' => 'required',
            'ip' => 'required',
            'text' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response([
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 500)
        );
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'client_id.required' => 'Client ID is required!',
            'user_id.required' => 'User ID is required!',
            'form_id.required' => 'Form ID is required!',
            'question_id.required' => 'Question ID is required!',
            'ip.required' => 'IP is required!',
            'text.required' => 'Text is required!'
        ];
    }
}
