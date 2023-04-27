<?php

namespace Intranet\Integration\Services;

use InvalidArgumentException;
use Intranet\Integration\Models\Integration;

abstract class BaseClientService
{
    public function __construct(protected Integration $integration)
    {
        if ($this->integration->type !== $this->getIntegrationType()) {
            throw new InvalidArgumentException();
        }
    }

    protected abstract function getIntegrationType(): string;
}
