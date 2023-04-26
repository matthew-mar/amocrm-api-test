<?php

namespace Intranet\AmoCrmApi;

use Illuminate\Support\Facades\App;
use AmoCRM\OAuth2\Client\Provider\AmoCRM;
use Intranet\AmoCrmApi\Exceptions\ApiModelNotFound;

/**
 * @property \Intranet\AmoCrmApi\Models\System $system
 */
class Client
{
    /** @var AmoCrm $provider */
    private $provider;

    public function init(string $baseDomain, string $clientId, string $clientSecret, string $redirectUri): self
    {
        $this->provider = new AmoCRM([
            'baseDomain' => $baseDomain,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
        ]);

        return $this;
    }

    public function __get($name)
    {
        $model = App::make("amocrm.model.{$name}", [$this->provider]) ?? null;

        if (! $model) {
            throw new ApiModelNotFound();
        }

        return $model;
    }
}
