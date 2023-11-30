<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            case 'POST':
                if ($this->is('api/products')) {
                    $rules = [
                        "page" => "required|integer",
                        "limit" => "required|integer",
                        "key" => "nullable|string",
                        "order" => "requiredWith:key|nullable|boolean",
                        "trash" => "required|boolean",
                        "search" => "nullable|string",
                    ];
                }

                if ($this->is('api/product')) {
                    $rules = [
                        "name" => "required|string",
                        "price" => "required|numeric",
                        "description" => "required|string"
                    ];
                }
                break;

            case 'GET':
                $rules = [
                    "id" => "required|integer|exists:products,id"
                ];
                break;

            case 'PUT':
            case 'PATCH':
                if ($this->is('api/product')) {
                    $rules = [
                        "id" => "required|integer|exists:products,id",
                        "name" => "required|string",
                        "price" => "required|numeric",
                        "description" => "required|string"
                    ];
                }

                if ($this->is('api/product/delete')) {
                    $rules = [
                        "id" => "required|integer|exists:products,id",
                    ];
                }

                if ($this->is('api/product/restore')) {
                    $rules = [
                        "id" => "required|integer|exists:products,id",
                    ];
                }
                break;

            case 'DELETE':
                $rules = [
                    "id" => "required|integer|exists:products,id",
                ];
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
