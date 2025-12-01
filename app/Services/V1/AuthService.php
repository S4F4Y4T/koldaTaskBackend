<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(
        protected JwtTokenService $tokenService
    ) {
    }
    
    public function login(array $credentials): ?string
    {
        $token = auth()->attempt($credentials);

        return $token ?: null;
    }
    
    public function logout(string $token): void
    {
        $this->tokenService->invalidateToken($token);
    }

    public function refresh(string $refreshToken): string
    {
        return $this->tokenService->refreshToken($refreshToken);
    }
    
    public function getAuthenticatedUser(): ?User
    {
        $user = auth()->user();

        if ($user) {
            $user->load('roles.permissions');
        }

        return $user;
    }
   
    public function generateRefreshToken(): string
    {
        return $this->tokenService->generateRefreshToken();
    }
    
    public function getTokenTTL(): int
    {
        return $this->tokenService->getTokenTTL();
    }
    
    public function getRefreshTokenTTL(): int
    {
        return $this->tokenService->getRefreshTokenTTL();
    }
}
