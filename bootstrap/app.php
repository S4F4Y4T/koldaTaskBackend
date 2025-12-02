<?php

use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\JwtMiddleware;
use App\Traits\V1\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '/api',
        then: function () {
            Route::middleware('api')
                ->prefix('/api/v1')
                ->name('v1.')
                ->group(base_path('routes/v1/api.php'));

            RateLimiter::for('jwt', function (Request $request) {
                return Limit::perMinute(60)->by(
                    $request->user()?->id ?: $request->ip()
                );
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'jwt.auth' => JwtMiddleware::class,
            'guest' => GuestMiddleware::class,
        ]);

        // Configure CORS
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, Request $request) {

            if ($e instanceof ParseError) {

                return ApiResponse::error($e->getMessage() ?? 'Something went wrong',
                    (int) $e->getCode() ?: 500
                );
            }
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Record not found', 404);
        });

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            $model = str_replace('App\\Models\\', '', $exception->getModel());

            return ApiResponse::error("The requested {$model} was not found.", 404);
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Unauthorized access', 403);
        });

        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Access denied', 403);
        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Unauthenticated', 401);
        });

        $exceptions->render(function (ValidationException $exception, Request $request) {

            return ApiResponse::error($exception->getMessage() ?? 'Validation Failed', 422, errors: $exception->errors());
        });

        $exceptions->render(function (UnauthorizedHttpException $exception, Request $request) {

            return ApiResponse::error($exception->getMessage() ?? 'Unauthorized Request', 422);
        });

        $exceptions->render(function (QueryException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Query Exception', 422);
        });

        $exceptions->render(function (TooManyRequestsHttpException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Too many requests', 429);
        });

        $exceptions->render(function (InvalidArgumentException $exception, Request $request) {
            return ApiResponse::error($exception->getMessage() ?? 'Invalid Argument', 400);
        });

        $exceptions->render(function (Exception $exception, Request $request) {

            info($exception);

            return ApiResponse::error($exception->getMessage() ?? 'Something went wrong',
                (int) $exception->getCode() ?: 500
            );

        });

    })->create();
