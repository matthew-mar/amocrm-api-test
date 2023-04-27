<?php

namespace Intranet\Integration\Services;

use AmoCRM\Models\LeadModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\{AmoCRMApiException, AmoCRMoAuthApiException};

use Intranet\Integration\Utils\Enum\IntegrationTypeEnum;
use Intranet\Integration\Contracts\AmoCrmClientService as AmoCrmClientServiceContract;

use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Token\{AccessToken, AccessTokenInterface};

class AmoCrmClientService extends BaseClientService implements AmoCrmClientServiceContract
{
    const INTEGRATION_TYPE = IntegrationTypeEnum::AMO_CRM;

    protected AmoCRMApiClient $client;

    public function authorizeByCode(string $code): AccessTokenInterface|null
    {
        try {
            return $this->getClient()->getOAuthClient()->getAccessTokenByCode($code);
        } catch (AmoCRMoAuthApiException $e) {
            Log::error($e);
            return null;
        }
    }

    public function refreshAuth(): AccessTokenInterface|null
    {
        try {
            return $this->getClient()
                ->getOAuthClient()
                ->getAccessTokenByRefreshToken($this->getAccessToken());
        } catch (AmoCRMoAuthApiException $e) {
            Log::error($e);
            return null;
        }
    }

    public function addLead(LeadModel $lead): LeadModel|null
    {
        try {
            return $this->getClient()
                ->setAccessToken($this->getAccessToken())
                ->leads()
                ->addOne($lead);
        } catch (AmoCRMApiException $e) {
            Log::error($e);
            return null;
        }
    }

    protected function getIntegrationType(): string
    {
        return self::INTEGRATION_TYPE->value;
    }

    protected function getClient(): AmoCRMApiClient
    {
        if (! isset($this->client)) {
            $integrationMeta = $this->integration->getMeta();

            $this->client = new AmoCRMApiClient(
                $this->integration->id,
                $integrationMeta['client_secret'],
                $integrationMeta['redirect_uri']
            );
            $this->client->setAccountBaseDomain($integrationMeta['base_domain']);
        }

        return $this->client;
    }

    protected function getAccessToken(): AccessToken
    {
        return new AccessToken($this->integration->getAuth());
    }
}
