<?php

namespace Winex01\BackpackPermissionManager\Http\Controllers;

use Spatie\Permission\PermissionRegistrar;
use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController as BackpackRoleCrudController;
use Backpack\PermissionManager\app\Http\Requests\RoleStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\RoleUpdateCrudRequest as UpdateRequest;

// VALIDATION

class RoleCrudController extends BackpackRoleCrudController
{
    public function setupCreateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(StoreRequest::class);

        //otherwise, changes won't have effect
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function setupUpdateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(UpdateRequest::class);

        //otherwise, changes won't have effect
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function addFields()
    {
        $this->crud->addField([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
        ]);

        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addField([
                'name'    => 'guard_name',
                'label'   => trans('backpack::permissionmanager.guard_type'),
                'type'    => 'select_from_array',
                'options' => $this->getGuardTypes(),
            ]);
        }

        $this->crud->addField([
            'label'             => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
            'type'              => 'role_checklist',
            'name'              => 'permissions',
            'entity'            => 'permissions',
            'attribute'         => 'name',
            'model'             => $this->permission_model,
            'pivot'             => true,
            'number_of_columns' => config('winex01.backpack-permission-manager.permissions_columns'),
        ]);
    }
}
