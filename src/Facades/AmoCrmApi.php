<?php

namespace Intranet\AmoCrmApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Intranet\AmoCrmApi\Client init(string $baseDomain, string $clientId, string $clientSecret, string $redirectUri)
 */
class AmoCrmApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'amocrm.client';
    }
}
