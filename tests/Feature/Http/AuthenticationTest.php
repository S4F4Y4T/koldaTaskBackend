<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;

//login

beforeEach(function () {
    // Create a test user
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
});

$invalidCredentials = [
    'invalid email' => [
        'email' => 'example@example.com',
        'password' => 'password',
        'errorField' => 'email',
    ],
    'empty email' => [
        'email' => '',
        'password' => 'password',
        'errorField' => 'email',
    ],
    'empty password' => [
        'email' => 'test@example.com',
        'password' => '',
        'errorField' => 'password',
    ],
];

it('fails validating login requests', function (string $email, string $password, string $errorField) {

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $email,
        'password' => $password,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([$errorField]);

})->with($invalidCredentials);

it('fails with invalid password', function () {

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => '123556789',
    ]);

    expect($response->status())->toBe(401)
        ->and($response->json('type'))->toBe('error')
        ->and($response->json('message'))->toBe('Invalid credentials.');
});

it('logs in successfully with valid credentials', function () {

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    expect($response->status())->toBe(200)
        ->and($response->json('type'))->toBe('success')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data')['access_token'])->toBeString()
        ->and($response->json('data')['access_token'])->not->toBeNull()
        ->and($response->json('data')['user'])->toBeArray();
});

it('fetches users data successfully with a valid token', function () {

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $token = $response->json('data')['access_token'];

    $usersResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson(route('v1.auth.me'));

    expect($usersResponse->status())->toBe(200)
        ->and($usersResponse->json('type'))->toBe('success')
        ->and($usersResponse->json('data'))->toBeArray();
});

it('fails with invalid token', function () {

    $usersResponse = $this->withHeaders([
        'Authorization' => 'Bearer something',
    ])->getJson(route('v1.auth.me'));

    expect($usersResponse->status())->toBe(401)
        ->and($usersResponse->json('type'))->toBe('error')
        ->and($usersResponse->json('message'))->toBe('Token not valid.');
});

it('rejects a request with an expired token', function () {
    Config::set('jwt.ttl', 1);

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    expect($response->status())->toBe(200);

    $token = $response->json('data')['access_token'];

    sleep(61);

    $usersResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson(route('v1.auth.me'));

    expect($usersResponse->status())->toBe(401)
        ->and($usersResponse->json('type'))->toBe('error')
        ->and($usersResponse->json('message'))->toBe('Token not valid.');
})->skip();

it('fails when logged in user login', function () {

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $token = $response->json('data')['access_token'];

    $usersResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson(route('v1.auth.login'), [
                'email' => $this->user->email,
                'password' => 'password',
            ]);

    expect($usersResponse->status())->toBe(403)
        ->and($usersResponse->json('message'))->toBe('You are already logged In.');
});

it('authenticate user logout successfully', function () {

    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $token = $response->json('data')['access_token'];

    $logoutResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson(route('v1.auth.logout'), [
                'email' => $this->user->email,
                'password' => 'password',
            ]);

    expect($logoutResponse->status())->toBe(200)
        ->and($logoutResponse->json('type'))->toBe('success')
        ->and($logoutResponse->json('message'))->toBe('Successfully logged out.');

    $usersResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson(route('v1.auth.me'));

    expect($usersResponse->status())->toBe(401)
        ->and($usersResponse->json('type'))->toBe('error')
        ->and($usersResponse->json('message'))->toBe('Token not valid.');
});

it('fails unauthenticated user from logout', function () {

    $logoutResponse = $this->postJson(route('v1.auth.logout'));


    expect($logoutResponse->status())->toBe(401)
        ->and($logoutResponse->json('type'))->toBe('error');
});

it('refresh token', function () {
    $response = $this->postJson(route('v1.auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    expect($response->status())->toBe(200);

    $accessToken = $response->json('data')['access_token'];
    $refreshToken = $response->headers->getCookies()[0]->getValue();

    $refreshResponse = $this->withHeader('Authorization', 'Bearer ' . $accessToken)
        ->withCookie('refresh_token', $refreshToken)
        ->postJson(route('v1.auth.refresh'));

    expect($refreshResponse->status())->toBe(200)
        ->and($refreshResponse->json('type'))->toBe('success')
        ->and($refreshResponse->json('data'))->toBeArray()
        ->and($refreshResponse->json('data')['access_token'])->toBeString()
        ->and($refreshResponse->json('data')['access_token'])->not->toBeNull();

})->skip();

