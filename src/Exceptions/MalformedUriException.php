<?php

namespace AdamQuaile\HypermediaApiClient\Exceptions;

class MalformedUriException extends \InvalidArgumentException
{
    public function __construct(string $uri)
    {
        parent::__construct("The requested URI is malformed or unsupported: $uri");
    }
}