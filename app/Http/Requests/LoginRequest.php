<?php

namespace App\Http\Requests;

use App\Exceptions\GeneralException;
use App\Traits\Responses;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class LoginRequest extends FormRequest
{

    use Responses;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string']
        ];
    }

    public function failure($validator): \Exception
    {
        $message = $validator->errors()->first();
        throw new GeneralException($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
