<?php

namespace App\Services\V1;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Service class for handling JWT token operations
 * 
 * This service centralizes all JWT token-related functionality including
 * token generation, refresh, and invalidation.
 */
class JwtTokenService
{
    /**
     * Generate an access token for the given user
     *
     * @param User $user The user to generate token for
     * @return string The generated JWT token
     */
    public function generateToken(User $user): string
    {
        return auth()->login($user);
    }

    /**
     * Generate a refresh token
     *
     * @return string The refresh token
     */
    public function generateRefreshToken(): string
    {
        return auth()->refresh();
    }

    /**
     * Invalidate the given token
     *
     * @param string $token The token to invalidate
     * @return void
     */
    public function invalidateToken(string $token): void
    {
        JWTAuth::invalidate(JWTAuth::setToken($token)->getToken());
    }

    /**
     * Refresh an access token using a refresh token
     *
     * @param string $refreshToken The refresh token
     * @return string The new access token
     * @throws \Tymon\JWTAuth\Exceptions\TokenExpiredException
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function refreshToken(string $refreshToken): string
    {
        return auth()->setToken($refreshToken)->refresh();
    }

    /**
     * Get the token time-to-live in seconds
     *
     * @return int Token TTL in seconds
     */
    public function getTokenTTL(): int
    {
        return auth()->factory()->getTTL() * 60;
    }

    /**
     * Get the refresh token time-to-live in minutes
     *
     * @return int Refresh token TTL in minutes
     */
    public function getRefreshTokenTTL(): int
    {
        return config('jwt.refresh_ttl', 20160); // Default 2 weeks
    }
}
