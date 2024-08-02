<?php

namespace Winex01\BackpackPermissionManager\Http\Controllers\Traits;

use Illuminate\Support\Str;

trait UserPermissions
{
    public function userPermissions($role = null)
    {   
        $this->crud->denyAllAccess();

        // check access for current role & admin
        $this->checkAccess($role);
        $this->checkAccess('admin');
        $this->checkAccess('menu_separator');
    }

    public function checkAccess($role)
    {
        $role = $role ?? $this->crud->model->getTable();

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