<?php

namespace Intranet\AmoCrmApi\Services;

use Illuminate\Database\Eloquent\Collection;
use Intranet\AmoCrmApi\Contracts\AmoCrmIntegrationRepository;
use Intranet\AmoCrmApi\Models\Eloquent\AmoCrmIntegration;
use Intranet\AmoCrmApi\Contracts\AmoCrmIntegrationService as AmoCrmIntegrationServiceContract;

class AmoCrmIntegrationService implements AmoCrmIntegrationServiceContract
{
    public function __construct(private AmoCrmIntegrationRepository $rep) {}

    public function create(array $fields): AmoCrmIntegration|null
    {
        return $this->rep->create($fields);
    }
    
    public function updateByModel(AmoCrmIntegration $integration, array $fields): bool
    {
        return $this->rep->updateByModel($integration, $fields);
    }

    public function findForOAuthRefresh(int $currentTimestamp, int $delta = 1000000): Collection
    {
        return $this->rep->findForOAuthRefresh($currentTimestamp, $delta);
    }
}
