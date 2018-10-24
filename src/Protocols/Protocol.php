<?php

namespace AdamQuaile\HypermediaApiClient\Protocols;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Model\Resource;

interface Protocol
{
    public function supportsProtocolString(string $protocol): bool;
    public function loadFromUri(string $uri, ApiClient $client): Resource;
}