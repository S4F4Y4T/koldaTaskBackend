<?php

use Illuminate\Auth\Access\AuthorizationException;

if (! function_exists('authorize')) {
    /**
     * @throws AuthorizationException
     */
    function authorize($permissions): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if (! $user->can($permissions)) {
            throw new AuthorizationException('Unauthorized user.');
        }

        return true;
    }
}
