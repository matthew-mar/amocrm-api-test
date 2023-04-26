<?php

namespace Intranet\AmoCrmApi\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Intranet\Database\Models\CoreModel;

/**
 * @property string|null $client_id
 * @property string|null $client_secret
 * @property string|null $base_domain
 * @property string|null $redirect_uri
 * @property array|null $auth
 */
class AmoCrmIntegration extends CoreModel
{
    public $incrementing = false;

    public $primaryKey = 'client_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = 'core.amocrm_integration';

    protected $fillable = [
        'client_id',
        'client_secret',
        'base_domain',
        'redirect_uri',
        'auth',
    ];

    public function getAuth(): array
    {
        return json_decode($this->auth, true);
    }
}
