<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class, 
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\TenantRouteServiceProvider::class,
    Laravel\Socialite\SocialiteServiceProvider::class,
];
