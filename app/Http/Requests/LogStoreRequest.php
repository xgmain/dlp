<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class LogStoreRequest extends FormRequest
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
            'rule_id' => 'required',
            'system' => [
                'required',
                Rule::in(['dlp']),
            ],
            'event' => [
                'required',
                Rule::in(['logs']),
            ],
            'severity' => [
                'required',
                Rule::in(['block', 'warning']),
            ],
            'ip' => 'required',
            'position' => 'required',
            'user_id' => 'required',
            'form_id' => 'required',
            'question_id' => 'required'
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
            'rule_id.required' => 'Rule ID is required!',
            'system.required' => 'System is required!',
            'event.required' => 'Event is required!',
            'severity.required' => 'Severity is required!',
            'ip.required' => 'Ip is required!',
            'position.required' => 'Position is required',
            'user_id.required' => 'User ID is required!',
            'form_id.required' => 'Form ID is required!',
            'question_id.required' => 'Question ID is required!',
        ];
    }
}
