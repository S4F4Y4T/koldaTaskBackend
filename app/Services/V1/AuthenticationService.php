<?php

namespace App\Services\V1;

class AuthenticationService
{
    public function login(array $credentials): ?array
    {
        $token = auth()->attempt($credentials);

        if (! $token) {
            return null;
        }

        $refreshToken = auth()->setTTL(config('jwt.refresh_ttl', 20160))
            ->tokenById(auth()->user()->id);

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ];
    }

    public function refresh(string $refreshToken): string
    {
        return auth()->setToken($refreshToken)->refresh();
    }
}
