<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Actions\V1\Roles\CreateRoleAction;
use App\Actions\V1\Roles\UpdateRoleAction;
use App\Filters\V1\RoleFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Role\StoreRoleRequest;
use App\Http\Requests\V1\Role\UpdateRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;
use App\Traits\V1\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    protected string $policyModel = Role::class;
    protected string $resource = RoleResource::class;
    protected string $filter = RoleFilter::class;

    /**
     * Display a listing of the resource.
     */
    public function index(RoleFilter $filters): AnonymousResourceCollection
    {
//        $this->isAuthorized('all');

        return RoleResource::collection(
            Role::query()->filter($filters)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(StoreRoleRequest $request, CreateRoleAction $action): JsonResponse
    {
//        $this->isAuthorized('create');

        $role = $action($request->validated());

        return self::success(message: "Role created successfully", data: $role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): RoleResource
    {
//        $this->isAuthorized('show', $role);

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $action): JsonResponse
    {
//        $this->isAuthorized('update', $role);

        $role = $action($role, $request->validated());

        return self::success(message: "Role updated successfully", data: $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
//        $this->isAuthorized('delete', $role);

        $role->delete();

        return self::success(message: "Role deleted successfully");
    }
}
