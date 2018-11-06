<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi;

use AdamQuaile\HypermediaApiClient\Extensions\Iteration\IterationStrategy;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\ResourceIterator;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class JsonApiIterationStrategy implements IterationStrategy
{

    public function supports(RequestInterface $request, ResponseInterface $response, Resource $resource): bool
    {
        return
            'application/vnd.api+json' === $response->getHeaderLine('Content-type') &&
            is_object($resource->getData()->get('deserialised')) &&
            \is_array($resource->getData()->get('deserialised')->data);
    }

    public function getIterator(RequestInterface $request, ResponseInterface $response, Resource $resource): \Iterator
    {
        return new ResourceIterator(
            $resource,
            function(Resource $resource) {
                return $resource->getData()->get('deserialised')->data;
            }
        );
    }
}