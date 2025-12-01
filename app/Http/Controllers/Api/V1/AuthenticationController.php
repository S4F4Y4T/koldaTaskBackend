<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\Authentication\LoginRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Http\Controllers\Api\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $token = auth()->attempt($request->validated());

        if (!$token) {
            return self::error('Invalid credentials.', 401);
        }

        return $this->respondWithToken($token)
            ->cookie(
                'refresh_token',
                auth()->refresh(),
                config('jwt.refresh_ttl', 20160),
                '/',
                null,
                true,
                true,
                false,
                'strict'
            );
    }

    public function refresh(Request $request): JsonResponse
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return self::error('Refresh token not provided.', 400);
        }

        try {
            
            return $this->respondWithToken(
                auth()->setToken($refreshToken)->refresh()
            );

        } catch (TokenExpiredException $e) {
            return self::error('Refresh token has expired.', 401);
        } catch (JWTException $e) {
            return self::error('Invalid refresh token.', 400);
        }
    }

    public function me(): JsonResponse
    {
        return self::success('Data fetched successfully', data: auth()->user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return self::success(message: 'Successfully logged out');
    }

    protected function respondWithToken($token): JsonResponse
    {
        return self::success(message: 'authentication successful',
            data:[
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => UserResource::make(
                    auth()->user()->load('roles.permissions')
                )
            ]);
    }
}
