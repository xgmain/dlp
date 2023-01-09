<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RuleUpdateRequest extends FormRequest
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
            'name' => 'required',
            'context' => 'required|unique:rules',
            'type' => [
                'required',
                Rule::in(['block', 'warning']),
            ],
            'message' => 'required',
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
            'name.required' => 'Name is required!',
            'context.required' => 'Context is required!',
            'context.unique' => 'Context is taken!',
            'type.required' => 'Type is required!',
            'message.required' => 'Message is required!',
        ];
    }
}
