<?php

namespace Intranet\Integration\Commands;

use Illuminate\Console\Command;

use Intranet\Integration\Models\Integration;
use Intranet\Database\Traits\ManagesDBTransactions;
use Intranet\Integration\Utils\Enum\IntegrationTypeEnum;
use Intranet\Integration\Contracts\{IntegrationService, AmoCrmClientService};

class AuthorizeAmoCrmIntegration extends Command
{
    use ManagesDBTransactions;

    public $signature = 'integration:authorize-amocrm-integration
                        {base_domain : Amo Crm account domain}
                        {client_id : Integration ID}
                        {client_secret : Client secret}
                        {redirect_uri : Redirect URI}
                        {authorization_code : Authorization code}';

    public $description = 'Авторизация интеграции с Amo Crm';

    public function handle()
    {
        $baseDomain = $this->argument('base_domain');
        $clientId = $this->argument('client_id');
        $clientSecret = $this->argument('client_secret');
        $redirectUri = $this->argument('redirect_uri');
        $authorizationCode = $this->argument('authorization_code');

        try {
            $this->startTransaction();

            $integrationService = $this->getIntegrationService();

            $integration = $integrationService->updateOrCreate($clientId, [
                'meta' => json_encode([
                    'client_secret' => $clientSecret,
                    'redirect_uri' => $redirectUri,
                    'base_domain' => $baseDomain,
                ]),
                'type' => IntegrationTypeEnum::AMO_CRM->value,
            ]);
            if (! $integration) {
                throw new \Exception('failed create integration');
            }

            $accessToken = $this->getAmoCrmService($integration)->authorizeByCode($authorizationCode);
            if (! $accessToken) {
                throw new \Exception('failed get access token');
            }

            $auth = json_encode([
                'access_token' => $accessToken->getToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
            ]);
            if (! $integrationService->updateByModel($integration, ['auth' => $auth])) {
                throw new \Exception('failed update integration auth');
            }

            $this->commit();
            $this->info('Amo Crm integration authorize successfully');
        } catch (\Throwable $e) {
            $this->rollBack($e);
            $this->error('failed authorize Amo Crm integration');
        }
    }

    private function getAmoCrmService(Integration $integration): AmoCrmClientService
    {
        return app(AmoCrmClientService::class, ['integration' => $integration]);
    }

    private function getIntegrationService(): IntegrationService
    {
        return app(IntegrationService::class);
    }
}
