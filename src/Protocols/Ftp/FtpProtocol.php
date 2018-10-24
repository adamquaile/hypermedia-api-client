<?php

namespace AdamQuaile\HypermediaApiClient\Protocols\Ftp;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Protocols\Protocol;

class FtpProtocol implements Protocol
{
    public function supportsProtocolString(string $protocol): bool
    {
        return in_array($protocol, ['ftp']);
    }

    public function loadFromUri(string $uri, ApiClient $client): Resource
    {
        // TODO: Implement loadFromUri() method.
    }
}