<?php

namespace Intranet\AmoCrmApi;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Intranet\AmoCrmApi\Models\System;

class AmoCrmApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind('amocrm.client', function () {
            return new Client();
        });

        $this->app->bind('amocrm.model.system', function ($app, array $params) {
            return new System($params[0]);
        });
    }

    public function provides()
    {
        return [
            'amocrm.client',
            'amocrm.model.system',
        ];
    }
}
