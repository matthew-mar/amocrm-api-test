<?php

namespace Intranet\Integration\Repositories;

use Intranet\Integration\Models\Integration;
use Intranet\Integration\Contracts\IntegrationRepository as IntegrationRepositoryContract;

use Illuminate\Database\Eloquent\Collection;

class IntegrationRepository extends Repository implements IntegrationRepositoryContract
{
    public function __construct(Integration $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreate(string $id, array $fields): Integration|null
    {
        return $this->getModel()->newQuery()->updateOrCreate(['id' => $id], $fields);
    }

    public function updateByModel(Integration $integration, array $fields): bool
    {
        return $integration->update($fields);
    }

    public function getById(string $id): Integration|null
    {
        return $this->getModel()
            ->newQuery()
            ->where('id', $id)
            ->first();
    }

    public function findByType(string $type): Collection
    {
        return $this->getModel()
            ->newQuery()
            ->where('type', $type)
            ->get();
    }
}
