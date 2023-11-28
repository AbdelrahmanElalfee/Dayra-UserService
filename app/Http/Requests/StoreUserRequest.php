<?php

namespace App\Http\Requests;

use App\Exceptions\GeneralException;
use App\Traits\Responses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{

    use Responses;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @throws GeneralException
     */
    public function failure($validator): \Exception
    {
        $message = $validator->errors()->first();
        throw new GeneralException($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
