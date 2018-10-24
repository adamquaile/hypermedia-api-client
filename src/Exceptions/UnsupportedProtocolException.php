<?php

namespace AdamQuaile\HypermediaApiClient\Exceptions;

class UnsupportedProtocolException extends \RuntimeException
{
    public function __construct(string $protocol)
    {
        parent::__construct("The protocol $protocol is not yet supported");
    }
}