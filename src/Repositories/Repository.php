<?php

namespace Intranet\Integration\Repositories;

use Illuminate\Database\Eloquent\Model;
use Intranet\Foundation\Support\Repository as BaseRepository;

abstract class Repository extends BaseRepository
{
    protected function makeExistingModel(array $attrs, bool $wasRecentlyCreated = false): Model
    {
        $model = $this->getModel()
            ->newInstance([], true)
            ->setRawAttributes($attrs, true);

        if ($wasRecentlyCreated) {
            $model->wasRecentlyCreated = true;
        }

        return $model;
    }
}
