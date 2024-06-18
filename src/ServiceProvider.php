<?php

namespace Suarez\StatamicUtmParameters;

use Statamic\Providers\AddonServiceProvider;
use Suarez\StatamicUtmParameters\UtmParameter;
use Suarez\StatamicUtmParameters\Http\Middleware\CheckUtmParameter;

class ServiceProvider extends AddonServiceProvider
{
    protected $middlewareGroups = [
        'web' => [
            CheckUtmParameter::class
        ],
    ];

    protected $tags = [
        \Suarez\StatamicUtmParameters\Tags\Utm::class
    ];

    public function bootAddon()
    {
        $this->app->singleton(UtmParameter::class, function () {
            return new UtmParameter();
        });
    }
}
