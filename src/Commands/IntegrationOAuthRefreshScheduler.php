<?php

namespace Intranet\Integration\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

use Intranet\Integration\Models\Integration;
use Intranet\Database\Traits\ManagesDBTransactions;
use Intranet\Integration\Utils\Enum\IntegrationTypeEnum;
use Intranet\Integration\Contracts\{IntegrationService, AmoCrmClientService};

class IntegrationOAuthRefreshScheduler extends Command
{
    use ManagesDBTransactions;

    public $signature = 'integration:oauth-scheduler
                        {--sleep=}
                        {--delta=}';

    public $description = 'Планировщик обнолвения токенов интеграций';

    public function handle()
    {
        $sleep = (int)$this->option('sleep') ?: 120;
        $delta = (int)$this->option('delta') ?: 600;

        while (true) {
            $now = Carbon::now()->timestamp;

            try {
                $this->startTransaction();

                $this->updateAmoCrmIntegrations($now, $delta);

                $this->commit();
            } catch (\Throwable $e) {
                $this->rollBack($e);
            }

            sleep($sleep);
        }
    }

    private function updateAmoCrmIntegrations(int $currentTimestamp, int $delta): void
    {
        $integrations = $this->getIntegrationService()->findByType(IntegrationTypeEnum::AMO_CRM->value);
        if ($integrations->isEmpty()) {
            return;
        }

        /** @var Integration $integration */
        foreach ($integrations as $integration) {
            $auth = $integration->getAuth();
            if ($auth['expires'] - $currentTimestamp > $delta) {
                continue;
            }

            $this->info("process refresh oauth token for Amo Crm integration[$integration->id]");

            $accessToken = $this->getAmoCrmClientService($integration)->refreshAuth();
            if (! $accessToken) {
                throw new \Exception('failed refresh auth token');
            }

            $auth = json_encode([
                'access_token' => $accessToken->getToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
            ]);
            if (! $this->getIntegrationService()->updateByModel($integration, ['auth' => $auth])) {
                throw new \Exception('failed update Amo Crm integration tokens');
            }

            $this->info("processed refresh oauth token for integration[$integration->id]");
        }
    }

    private function getIntegrationService(): IntegrationService
    {
        return app(IntegrationService::class);
    }

    private function getAmoCrmClientService(Integration $integration): AmoCrmClientService
    {
        return app(AmoCrmClientService::class, ['integration' => $integration]);
    }
}
