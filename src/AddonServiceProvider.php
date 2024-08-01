<?php

namespace Winex01\BackpackPermissionManager;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'winex01';
    protected $packageName = 'backpack-permission-manager';
    protected $commands = [];
}
