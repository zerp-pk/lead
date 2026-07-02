<?php

namespace Zerp\Lead\Providers;

use App\Events\DefaultData;
use App\Events\GivePermissionToRole;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Zerp\Lead\Listeners\DataDefault;
use Zerp\Lead\Listeners\GiveRoleToPermission;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        GivePermissionToRole::class => [
            GiveRoleToPermission::class,
        ],
        DefaultData::class => [
            DataDefault::class,
        ],
    ];
}