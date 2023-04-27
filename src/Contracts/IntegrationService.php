<?php

namespace Intranet\Integration\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Intranet\Integration\Models\Integration;

interface IntegrationService
{
    public function updateOrCreate(string $id, array $fields): Integration|null;

    public function updateByModel(Integration $integration, array $fields): bool;

    public function getById(string $id): Integration|null;

    public function findByType(string $type): Collection;
}