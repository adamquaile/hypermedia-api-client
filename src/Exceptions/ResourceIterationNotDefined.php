<?php

namespace AdamQuaile\HypermediaApiClient\Exceptions;

use AdamQuaile\HypermediaApiClient\Model\Resource;

class ResourceIterationNotDefined extends \RuntimeException
{
    public function __construct(Resource $resource)
    {
        parent::__construct("Resource cannot be iterated because no plugins registered an iterator");
    }
}