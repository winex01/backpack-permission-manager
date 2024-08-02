<?php

namespace Winex01\BackpackPermissionManager\Http\Controllers\Traits;

use Illuminate\Support\Str;

trait UserPermission
{
    public function userPermission($role = null)
    {
        if (!$role) {
            $role = $this->crud->model->getTable();
        }

        // check access for current role & admin
        $this->checkAccess($this->role);
        $this->checkAccess('admin');
        $this->checkAccess('menu_separator');
    }

    public function checkAccess($role)
    {
        $allRolePermissions = config('permission.models.permission')
            ::where('name', 'LIKE', "$role%")
            ->pluck('name')->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                return $value;
            })->toArray();

        // deny all access first
        $this->crud->denyAccess($allRolePermissions);

        $permissions = auth()->user()->getAllPermissions()
            ->pluck('name')
            ->filter(function ($item) use ($role) {
                return false !== stristr($item, $role);
            })->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                $value = Str::camel($value);
                return $value;
            })->toArray();

        // allow access if user have permission
        $this->crud->allowAccess($permissions);
    }
}