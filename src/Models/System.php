<?php

namespace Intranet\AmoCrmApi\Models;

use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Token\AccessTokenInterface;

class System extends AbstractModel
{
    public function authorizeByCode(string $code): AccessTokenInterface
    {
        return $this->provider->getAccessToken(new AuthorizationCode(), [
            'code' => $code,
        ]);
    }
}
