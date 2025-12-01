<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Service class for handling authentication operations
 * 
 * This service encapsulates all authentication business logic including
 * login, logout, token refresh, and user retrieval.
 */
class AuthService
{
    /**
     * Create a new AuthService instance
     *
     * @param JwtTokenService $tokenService
     */
    public function __construct(
        protected JwtTokenService $tokenService
    ) {
    }

    /**
     * Attempt to authenticate a user with the given credentials
     *
     * @param array $credentials User credentials (email and password)
     * @return string|null JWT token if successful, null otherwise
     */
    public function login(array $credentials): ?string
    {
        $token = auth()->attempt($credentials);

        return $token ?: null;
    }

    /**
     * Log out the user by invalidating their token
     *
     * @param string $token The token to invalidate
     * @return void
     */
    public function logout(string $token): void
    {
        $this->tokenService->invalidateToken($token);
    }

    /**
     * Refresh the user's access token
     *
     * @param string $refreshToken The refresh token
     * @return string The new access token
     * @throws \Tymon\JWTAuth\Exceptions\TokenExpiredException
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function refresh(string $refreshToken): string
    {
        return $this->tokenService->refreshToken($refreshToken);
    }

    /**
     * Get the currently authenticated user with their roles and permissions
     *
     * @return User|null The authenticated user or null
     */
    public function getAuthenticatedUser(): ?User
    {
        $user = auth()->user();

        if ($user) {
            $user->load('roles.permissions');
        }

        return $user;
    }

    /**
     * Generate a refresh token for the current session
     *
     * @return string The refresh token
     */
    public function generateRefreshToken(): string
    {
        return $this->tokenService->generateRefreshToken();
    }

    /**
     * Get token time-to-live in seconds
     *
     * @return int Token TTL in seconds
     */
    public function getTokenTTL(): int
    {
        return $this->tokenService->getTokenTTL();
    }

    /**
     * Get refresh token time-to-live in minutes
     *
     * @return int Refresh token TTL in minutes
     */
    public function getRefreshTokenTTL(): int
    {
        return $this->tokenService->getRefreshTokenTTL();
    }
}
