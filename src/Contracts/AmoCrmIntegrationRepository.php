<?php

namespace Intranet\AmoCrmApi\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Intranet\AmoCrmApi\Models\Eloquent\AmoCrmIntegration;

interface AmoCrmIntegrationRepository
{
    public function create(array $fields): AmoCrmIntegration|null;

    public function updateByModel(AmoCrmIntegration $integration, array $fields): bool;

    public function findForOAuthRefresh(int $currentTimestamp, int $delta = 1000000): Collection;
}
