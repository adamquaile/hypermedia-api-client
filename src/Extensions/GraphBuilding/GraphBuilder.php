<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\GraphBuilding;

use AdamQuaile\HypermediaApiClient\Model\Graph;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface GraphBuilder
{
    public function supports(Resource $resource, ResponseInterface $response, RequestInterface $request): bool;
    public function buildGraph($data, Graph $graph): Graph;
}