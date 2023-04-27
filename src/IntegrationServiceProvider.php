<?php

namespace Intranet\Integration;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

use InvalidArgumentException;
use Intranet\Integration\{Contracts, Services, Repositories};

class IntegrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function register()
    {
        $this->app->bind(Contracts\AmoCrmClientService::class, function ($app, $parameters) {
            $integration = $parameters['integration'] ?? null;
            if (! $integration) {
                throw new InvalidArgumentException('Integration not defined');
            }

            return new Services\AmoCrmClientService($integration);
        });

        $this->app->bind(
            Contracts\IntegrationService::class,
            Services\IntegrationService::class
        );

        $this->app->bind(
            Contracts\IntegrationRepository::class,
            Repositories\IntegrationRepository::class
        );
    }

    public function provides()
    {
        return [
            Contracts\AmoCrmClientService::class,
            Contracts\IntegrationService::class,
            Contracts\IntegrationService::class,
        ];
    }
}