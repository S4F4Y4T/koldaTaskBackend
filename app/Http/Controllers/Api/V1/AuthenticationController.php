<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Requests\V1\Authentication\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

/**
 * Authentication Controller
 * 
 * Handles all authentication-related HTTP requests including login,
 * logout, token refresh, and user profile retrieval.
 */
class AuthenticationController
{
    use ApiResponse;

    /**
     * Create a new AuthenticationController instance
     *
     * @param AuthService $authService
     */
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * Authenticate a user and return JWT token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            $request->only('email', 'password')
        );

        if (!$token) {
            return self::error('Invalid credentials.', 401);
        }

        return $this->respondWithToken($token)
            ->cookie(
                'refresh_token',
                $this->authService->generateRefreshToken(),
                $this->authService->getRefreshTokenTTL(),
                '/',
                null,
                true,
                true,
                false,
                'strict'
            );
    }

    /**
     * Refresh the user's access token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return self::error('Refresh token not provided.', 400);
        }

        try {
            $newToken = $this->authService->refresh($refreshToken);

            return $this->respondWithToken($newToken);

        } catch (TokenExpiredException $e) {
            return self::error('Refresh token has expired.', 401);
        } catch (JWTException $e) {
            return self::error('Invalid refresh token.', 400);
        }
    }

    /**
     * Get the authenticated user's profile
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        return self::success(
            'User profile retrieved successfully.',
            data: UserResource::make($user)
        );
    }

    /**
     * Log out the authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            $this->authService->logout($token);
        }

        return self::success('Successfully logged out.')
            ->withCookie(cookie()->forget('refresh_token'));
    }

    /**
     * Create a standardized token response
     *
     * @param string $token The JWT access token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        return self::success(
            'Authentication successful.',
            data: [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->authService->getTokenTTL(),
                'user' => UserResource::make($user)
            ]
        );
    }
}
