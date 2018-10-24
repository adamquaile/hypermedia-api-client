<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Iteration;

use AdamQuaile\HypermediaApiClient\Model\Resource;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IterationStrategy
{
    public function supports(RequestInterface $request, ResponseInterface $response, Resource $resource): bool;
    public function getIterator(RequestInterface $request, ResponseInterface $response, Resource $resource): \Iterator;
}