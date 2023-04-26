<?php

namespace Intranet\AmoCrmApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Intranet\AmoCrmApi\Contracts\AmoCrmIntegrationService;
use Intranet\AmoCrmApi\Facades\AmoCrmApi;
use Intranet\AmoCrmApi\Models\Eloquent\AmoCrmIntegration;
use Intranet\Database\Traits\ManagesDBTransactions;

class RefreshIntegrationOAuthTokenScheduler extends Command
{
    use ManagesDBTransactions;

    public $signature = 'amocrm:oauth-scheduler {--sleep=}';

    public $description = 'Планировщик обновления токенов интеграции Amo Crm';

    public function handle()
    {
        $sleep = (int)$this->option('sleep') ?: 1;
        $service = $this->getAmoCrmIntegrationService();

        while (true) {
            $now = Carbon::now()->timestamp;
            $integrations = $service->findForOAuthRefresh($now);

            if ($integrations->isEmpty()) {
                sleep($sleep);
                continue;
            }

            /** @var AmoCrmIntegration $integration */
            foreach ($integrations as $integration) {
                $this->info("process refresh oauth token for integration[$integration->client_id]");

                try {
                    $this->startTransaction();

                    $this->updateOAuthToken($integration);
                    $this->info("processed refresh oauth token for integration[$integration->client_id]");

                    $this->commit();
                } catch (\Throwable $e) {
                    $this->rollBack($e);

                    sleep($sleep);
                }
            }
        }
    }

    private function updateOAuthToken(AmoCrmIntegration $integration)
    {
        $client = AmoCrmApi::init(
            $integration->base_domain,
            $integration->client_id,
            $integration->client_secret,
            $integration->redirect_uri
        );

        $refreshToken = $integration->getAuth()['refresh_token'];

        $accessToken = $client->system->refreshAuth($refreshToken);

        $auth = [
            'access_token' => $accessToken->getToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires' => $accessToken->getExpires(),
        ];

        if (! $this->getAmoCrmIntegrationService()->updateByModel($integration, ['auth' => $auth])) {
            $this->error("failed refresh oauth token for integration[$integration->client_secret]");
        }
    }

    private function getAmoCrmIntegrationService(): AmoCrmIntegrationService
    {
        return app(AmoCrmIntegrationService::class);
    }
}
