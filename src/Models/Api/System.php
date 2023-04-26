<?php

namespace Intranet\AmoCrmApi\Models\Api;

use League\OAuth2\Client\Grant\RefreshToken;
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
    
    public function refreshAuth(string $refreshToken): AccessTokenInterface
    {
        return $this->provider->getAccessToken(new RefreshToken(), [
            'refresh_token' => $refreshToken
        ]);
    }
}
