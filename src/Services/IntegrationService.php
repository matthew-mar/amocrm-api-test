<?php

namespace Intranet\Integration\Services;

use Intranet\Integration\Models\Integration;
use Intranet\Integration\Contracts\IntegrationRepository;
use Intranet\Integration\Contracts\IntegrationService as IntegrationServiceContract;

use Illuminate\Database\Eloquent\Collection;

class IntegrationService implements IntegrationServiceContract
{
    public function __construct(private IntegrationRepository $rep) {}

    public function updateOrCreate(string $id, array $fields): Integration|null
    {
        return $this->rep->updateOrCreate($id, $fields);
    }

    public function updateByModel(Integration $integration, array $fields): bool
    {
        return $this->rep->updateByModel($integration, $fields);
    }

    public function getById(string $id): Integration|null
    {
        return $this->rep->getById($id);
    }

    public function findByType(string $type): Collection
    {
        return $this->rep->findByType($type);
    }
}
