<?php

namespace Intranet\Integration\Models;

use Intranet\Database\Models\CoreModel;

/**
 * @property string|null $id
 * @property string|null $type
 * @property array|null $meta
 * @property array|null $auth
 */
class Integration extends CoreModel
{
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'auth',
        'meta',
        'type',
    ];

    public function getAuth(): array
    {
        return json_decode($this->auth, true);
    }

    public function getMeta(): array
    {
        return json_decode($this->meta, true);
    }
}