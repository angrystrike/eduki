<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    protected function failedValidation(Validator $validator): JsonResponse
    {
        $formattedErrors = [];
        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                $formattedErrors[] = [
                    'field' => $field . ' hi',
                    'message' => $message,
                ];
            }
        }

        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Validation failed123',
                'data' => $formattedErrors,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
