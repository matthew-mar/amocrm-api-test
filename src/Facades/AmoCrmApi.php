<?php

namespace Intranet\AmoCrmApi\Facades;

use Illuminate\Support\Facades\Facade;

class AmoCrmApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'amocrm.client';
    }
}
