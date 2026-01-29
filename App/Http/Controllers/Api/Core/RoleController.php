<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Helpers\ApiFormatter;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            $dashboardType = $user->dashboard_type ?? null;
            $userCompanyId = $user->company_id ?? null;

            $query = Role::query();

            // Jika admin → paksa filter company_id user sendiri
            if ($dashboardType === 'admin') {
                $query->where('company_id', $userCompanyId);
            }

            // Jika superadmin → boleh filter manual via ?company_id=
            if ($dashboardType === 'superadmin') {
                if (request()->has('company_id') && request()->company_id !== 'all') {
                    $query->where('company_id', request()->company_id);
                }
            }

            $data = $query->orderBy('id', 'desc')->get();
            return ApiFormatter::success($data, 'Roles fetched');

        } catch (\Exception $e) {
            return ApiFormatter::error($e->getMessage(), 500);
        }
    }


    public function store(StoreRoleRequest $request)
    {
        $payload = $request->validated();
        $payload['created_by'] = auth()->id() ?? null;
        $role = Role::create($payload);
        return ApiFormatter::success($role, 'Role created', 201);
    }

    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) return ApiFormatter::error('Role not found', 404);
        return ApiFormatter::success($role, 'Role found');
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::find($id);
        if (!$role) return ApiFormatter::error('Role not found', 404);
        $payload = $request->validated();
        $payload['updated_by'] = auth()->id() ?? null;
        $role->update($payload);
        return ApiFormatter::success($role, 'Role updated');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) return ApiFormatter::error('Role not found', 404);
        $role->delete();
        return ApiFormatter::success(null, 'Role deleted', 204);
    }
}
