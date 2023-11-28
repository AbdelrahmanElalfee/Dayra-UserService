<?php

namespace App\Repositories;

use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface {

    public function login(array $attributes): JsonResponse;

    public function register(array $attributes): JsonResponse;

    public function logout(): JsonResponse;

    public function me(): JsonResponse;

    public function createToken($token): JsonResponse;
}
