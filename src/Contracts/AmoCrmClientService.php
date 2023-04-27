<?php

namespace Intranet\Integration\Contracts;

use AmoCRM\Models\LeadModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

interface AmoCrmClientService
{
    public function authorizeByCode(string $code): AccessTokenInterface|null;

    public function refreshAuth(): AccessTokenInterface|null;

    public function addLead(LeadModel $lead): LeadModel|null;
}