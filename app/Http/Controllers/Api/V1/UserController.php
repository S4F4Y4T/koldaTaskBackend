<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\UserDTO;
use App\Filters\V1\UserFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\User\StoreUserRequest;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    protected string $policyModel = User::class;

    public function index(UserFilter $filter): AnonymousResourceCollection
    {
        $this->isAuthorized('all');

        return UserResource::collection(
            User::query()->filter($filter)->paginate()
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->isAuthorized('create');

        $dto = UserDTO::fromRequest($request);
        $user = User::create($dto->toArray());

        return self::success(message: 'User created successfully', code: 201, data: UserResource::make($user));
    }

    public function show(User $user): JsonResource
    {
        $this->isAuthorized('show', $user);

        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->isAuthorized('update', $user);

        $dto = UserDTO::fromRequest($request);
        tap($user)->update($dto->toArray());

        return self::success(message: 'User updated successfully', data: new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->isAuthorized('delete', $user);
        $user->delete();

        return self::success(message: 'User deleted successfully', code: 204);
    }
}
