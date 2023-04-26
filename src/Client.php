<?php

namespace Intranet\AmoCrmApi;

use AmoCRM\OAuth2\Client\Provider\AmoCRM;

class Client
{
    public function init(string $baseDomain, string $clientId, string $clientSecret, string $redirectUri): AmoCRM
    {
        return new AmoCRM([
            'baseDomain' => $baseDomain,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
        ]);
    }
}
