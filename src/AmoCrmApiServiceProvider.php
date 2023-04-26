<?php

namespace Intranet\AmoCrmApi;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AmoCrmApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind('amocrm.client', function () {
            return new Client();
        });
    }
}
