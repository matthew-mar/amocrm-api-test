<?php

namespace Intranet\AmoCrmApi;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Intranet\AmoCrmApi\{Contracts, Models, Services, Repositories};

class AmoCrmApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    private function registerModels()
    {
        $this->app->bind('amocrm.client', function () {
            return new Client();
        });

        $this->app->bind('amocrm.model.system', function ($app, array $params) {
            return new Models\Api\System($params[0]);
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function register()
    {
        $this->registerModels();

        $this->app->bind(
            Contracts\AmoCrmIntegrationService::class,
            Services\AmoCrmIntegrationService::class
        );

        $this->app->bind(
            Contracts\AmoCrmIntegrationRepository::class,
            Repositories\AmoCrmIntegrationRepository::class
        );
    }

    public function provides()
    {
        return [
            'amocrm.client',
            'amocrm.model.system',

            Contracts\AmoCrmIntegrationService::class,
            Contracts\AmoCrmIntegrationRepository::class,
        ];
    }
}
