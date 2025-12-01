<?php

namespace App\Http\Controllers\Api\V1\Admin;

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
    protected string $filter = RoleFilter::class;

    /**
     * Display a listing of the resource.
     */
    public function index(RoleFilter $filter): AnonymousResourceCollection
    {
        $this->isAuthorized('all');
        return RoleResource::collection(
            Role::query()->filter($filter)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $this->isAuthorized('create');
        $role = Role::create($request->validated());
        return self::success(message: 'Role created successfully', code: 201, data: RoleResource::make($role));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        $this->isAuthorized('show', $role);
        return self::success(message: 'Role fetched successfully', data: RoleResource::make($role->load('permissions')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $this->isAuthorized('update', $role);
        $role->update($request->validated());
        return self::success(message: 'Role updated successfully', data: RoleResource::make($role));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->isAuthorized('delete', $role);
        $role->delete();
        return self::success(message: 'Role deleted successfully');
    }

    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        $this->isAuthorized('update', $role); // Assuming update permission covers assigning permissions
        
        $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,name']
        ]);

        $role->assignPermission($request->permissions);

        return self::success(message: 'Permissions assigned successfully', data: RoleResource::make($role->load('permissions')));
    }
}
