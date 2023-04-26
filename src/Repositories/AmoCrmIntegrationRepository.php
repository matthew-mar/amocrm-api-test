<?php

namespace Intranet\AmoCrmApi\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Intranet\AmoCrmApi\Contracts\AmoCrmIntegrationRepository as AmoCrmIntegrationRepositoryContract;
use Intranet\AmoCrmApi\Models\Eloquent\AmoCrmIntegration;

class AmoCrmIntegrationRepository extends Repository implements AmoCrmIntegrationRepositoryContract
{
    public function __construct(AmoCrmIntegration $model)
    {
        parent::__construct($model);
    }

    public function create(array $fields): AmoCrmIntegration|null
    {
        return $this->getModel()->create($fields);
    }

    public function updateByModel(AmoCrmIntegration $integration, array $fields): bool
    {
        return $integration->update($fields);
    }

    public function findForOAuthRefresh(int $currentTimestamp, int $delta = 1000000): Collection
    {
        return $this->getModel()
            ->newQuery()
            ->whereRaw("json_typeof(json_extract_path(auth, 'expires'))::text != 'null'")
            ->whereRaw("json_extract_path(auth, 'expires')::jsonb::int - $currentTimestamp < $delta")
            ->get();
    }
}
