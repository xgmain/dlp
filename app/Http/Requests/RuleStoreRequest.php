<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RuleStoreRequest extends FormRequest
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
            'policy_id' => 'required',
            'context' => 'required|unique:rules',
            'status' => [
                'required',
                Rule::in(['block', 'warning']),
            ],
            'type' => [
                'required',
                Rule::in(['regex', 'keywords']),
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
            'policy_id.required' => 'Policy ID is required!',
            'context.required' => 'Context is required!',
            'context.unique' => 'Context is taken!',
            'type.required' => 'Type is required!',
            'status.required' => 'Status is required!',
            'message.required' => 'Message is required!',
        ];
    }
}
