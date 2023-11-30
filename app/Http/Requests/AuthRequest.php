<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        switch ($this->method()) {
            case "POST":
                if ($this->is('api/login')) {
                    $rules = [
                        "email" => "required|exists:users,email",
                        "password" => [
                            "required",
                            "string",
                            Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                                ->uncompromised()
                        ]
                    ];
                }

                if ($this->is('api/register')) {
                    $rules = [
                        "name" => "required|string",
                        "email" => "required|email|unique:users,email",
                        "roles" => "required|in:buyer,seller",
                        "password" => [
                            "required",
                            "string",
                            "same:confirm_password",
                            Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                                ->uncompromised()
                        ],
                        "confirm_password" => [
                            "required",
                            "string",
                            Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                                ->uncompromised()
                        ]
                    ];
                }
                break;
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            "status" => 422,
            "message" => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response)->setStatusCode(422));
    }
}
