<?php

namespace App\Http\Middleware;

use App\Traits\V1\ApiResponse;
use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        try {
            auth('api')->setToken($request->bearerToken())->authenticate();
        } catch (TokenExpiredException $e) {
            return self::error('Token expired.', 401);
        } catch (TokenInvalidException $e) {
            return self::error('Token not valid.', 401);
        } catch (JWTException $e) {
            return self::error('Token not found.', 401);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return self::error('Token not valid.', 401);
        } catch (Exception $e) {
            return self::error('Invalid Token', 500);
        }

        return $next($request);
    }
}
