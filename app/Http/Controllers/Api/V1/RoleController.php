<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\RoleDTO;
use App\Filters\V1\RoleFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Role\StoreRoleRequest;
use App\Http\Requests\V1\Role\UpdateRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    protected string $policyModel = Role::class;

    public function index(RoleFilter $filter): AnonymousResourceCollection
    {
        $this->isAuthorized('all');

        return RoleResource::collection(
            Role::query()->filter($filter)->paginate()
        );
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $this->isAuthorized('create');

        $dto = RoleDTO::fromRequest($request);
        $role = Role::create($dto->toArray());

        return self::success(message: 'Role created successfully', code: 201, data: RoleResource::make($role));
    }

    public function show(Role $role): JsonResponse
    {
        $this->isAuthorized('show', $role);

        return self::success(message: 'Role fetched successfully', data: RoleResource::make($role->load('permissions')));
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $this->isAuthorized('update', $role);

        $dto = RoleDTO::fromRequest($request);
        $role->update($dto->toArray());

        return self::success(message: 'Role updated successfully', data: RoleResource::make($role));
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->isAuthorized('delete', $role);
        $role->delete();

        return self::success(message: 'Role deleted successfully');
    }

    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        $this->isAuthorized('update', $role);

        $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->assignPermission($request->permissions);

        return self::success(message: 'Permissions assigned successfully', data: RoleResource::make($role->load('permissions')));
    }
}
