<?php

namespace Intranet\AmoCrmApi\Commands;

use Illuminate\Console\Command;
use Intranet\AmoCrmApi\Facades\AmoCrmApi;

class AuthorizeIntegration extends Command
{
    public $signature = 'amocrm:authorize-integration
                        {base_domain : Amo Crm account domain}
                        {client_id : Integration ID}
                        {client_secret : Client secret}
                        {redirect_uri : Redirect URI}
                        {authorization_code : Authorization code}';

    public $description = 'Authorize integration with Amo Crm';

    public function handle()
    {
        $baseDomain = $this->argument('base_domain');
        $clientId = $this->argument('client_id');
        $clientSecret = $this->argument('client_secret');
        $redirectUri = $this->argument('redirect_uri');
        $authorizationCode = $this->argument('authorization_code');

        $client = AmoCrmApi::init($baseDomain, $clientId, $clientSecret, $redirectUri);
        
        $accessToken = $client->system->authorizeByCode($authorizationCode);

        var_dump($accessToken);
    }
}
