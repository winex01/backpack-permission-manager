<?php

namespace Winex01\BackpackPermissionManager\Http\Controllers\Operations;

use Illuminate\Support\Str;

trait UserPermissionOperation
{
    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupUserPermissionDefaults()
    {
        $this->crud->operation('list', function() {
            $this->setupUserPermissionOperation();
        });
    }

    public function role() : string
    {
        return $this->crud->model->getTable();
    }

    public function roles() : array
    {
        return [];
    }

    private function setupUserPermissionOperation()
    {
        // check access for current role & admin
        $this->checkAccess($this->role());
        $this->checkAccess('admin');
        $this->checkAccess('menu_separator');

        if (!empty($this->roles())) {
            foreach ($this->roles() as $role) {
                $this->checkAccess($role);
            }
        }
    }

    private function checkAccess($role)
    {
        $allRolePermissions = config('permission.models.permission')
            ::where('name', 'LIKE', "$role%")
            ->pluck('name')->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                $value = Str::camel($value);
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