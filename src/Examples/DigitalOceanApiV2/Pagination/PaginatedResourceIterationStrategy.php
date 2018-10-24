<?php

namespace AdamQuaile\HypermediaApiClient\Examples\DigitalOceanApiV2\Pagination;

use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\IterationStrategy;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\PaginatedIterator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class PaginatedResourceIterationStrategy implements IterationStrategy
{

    private function getLastPathPart(UriInterface $uri)
    {
        $pathParts = preg_split('#/#', $uri->getPath());
        if (!is_array($pathParts) || count($pathParts) === 0) {
            return false;
        }
        $lastPathPart = $pathParts[count($pathParts) - 1];
        return $lastPathPart;
    }

    public function supports(RequestInterface $request, ResponseInterface $response, Resource $resource): bool
    {
        $lastPathPart = $this->getLastPathPart($request->getUri());
        return ($lastPathPart && isset($resource->getData()->get('deserialised')->$lastPathPart));
    }

    public function getIterator(RequestInterface $request, ResponseInterface $response, Resource $resource): \Iterator
    {
        return new PaginatedIterator(
            $resource,
            function(Resource $resource) use ($request) {
                $lastPathPart = $this->getLastPathPart($request->getUri());
                return $resource->getData()->get('deserialised')->$lastPathPart;
            },
            'next'
        );
    }
}