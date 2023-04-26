<?php

namespace Intranet\AmoCrmApi\Commands;

use Illuminate\Support\Str;
use Intranet\AmoCrmApi\Models\Eloquent\AmoCrmIntegration;

class Test extends \Illuminate\Console\Command
{
    public $signature = 'test';

    public function handle()
    {
//        dd(config('database.connection'));

        $mode = new AmoCrmIntegration([
            'client_id' => Str::uuid(),
            'client_secret' => '1',
            'base_domain' => '1',
            'redirect_uri' => '1',
            'auth' => json_encode([]),
        ]);

        $mode->save();
        
        
    }
}
