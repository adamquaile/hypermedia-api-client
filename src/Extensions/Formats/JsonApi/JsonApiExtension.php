<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use AdamQuaile\HypermediaApiClient\Model\DataSet;
use AdamQuaile\HypermediaApiClient\ServiceContainer;
use Psr\Http\Message\ResponseInterface;

class JsonApiExtension implements Extension, LinkParser
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $container->track($this);
    }

    /**
     * @return Link[]
     */
    public function parseLinks(ResponseInterface $response, DataSet $dataSet): \Traversable
    {
        if ('application/vnd.api+json' !== $response->getHeaderLine('Content-type')) {
            return;
        }

        foreach ($dataSet->get('deserialised')->data;
    }
}