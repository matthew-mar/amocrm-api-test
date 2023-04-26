<?php

namespace Intranet\AmoCrmApi\Models\Api;

use AmoCRM\OAuth2\Client\Provider\AmoCRM;

abstract class AbstractModel
{
    /**
     * @var AmoCRM $provider
     */
    protected $provider;

    public function __construct(AmoCRM $provider)
    {
        $this->provider = $provider;
    }
}
