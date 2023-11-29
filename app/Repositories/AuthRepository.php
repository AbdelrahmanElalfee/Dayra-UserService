<?php

namespace App\Repositories;

use App\Jobs\UserCreated;
use App\Models\User;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AuthRepository implements AuthRepositoryInterface {

    use Responses;

    /**
     * @throws GeneralException
     * @throws JWTException
     */
    public function login(array $attributes): JsonResponse
    {
        try {
            if(!$token = Auth::attempt($attributes)){
                throw new GeneralException("Wrong credentials", Response::HTTP_UNAUTHORIZED);
            }
            return $this->createToken($token);
        } catch (JWTException $e) {
            throw new JWTException($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @throws GeneralException
     */
    public function register(array $attributes): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email'),
                'password' => Hash::make(data_get($attributes, 'password')),
            ]);

            DB::commit();
            UserCreated::dispatch($user->toArray());
            return $this->createToken(Auth::attempt([
                    'email' => data_get($attributes, 'email'),
                    'password' => data_get($attributes, 'password')
                ]));
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GeneralException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->success(null,'You have successfully been logged out.', Response::HTTP_NO_CONTENT);
    }

    public function me(): JsonResponse
    {
        $user = User::find(Auth::id());
        return $this->success($user->id,'Authenticated');
    }

    public function createToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'user' => new UserResource(Auth::user())
        ], Response::HTTP_OK);
    }
}
