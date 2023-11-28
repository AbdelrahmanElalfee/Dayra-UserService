<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\AuthRepository;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use Responses;

    /**
     * @throws GeneralException
     * @throws JWTException
     */
    public function login(LoginRequest $request, AuthRepository $repository): JsonResponse
    {
        return $repository->login($request->toArray());
    }

    /**
     * @throws GeneralException
     */
    public function register(StoreUserRequest $request, AuthRepository $repository): JsonResponse
    {
        return $repository->register($request->toArray());
    }

    public function logout(AuthRepository $repository): JsonResponse
    {
        return $repository->logout();
    }

    public function me(AuthRepository $repository): JsonResponse
    {
        return $repository->me();
    }

}
