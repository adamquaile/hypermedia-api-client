<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\Hal;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\HypermediaExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use AdamQuaile\HypermediaApiClient\ServiceContainer;
use Psr\Http\Message\ResponseInterface;

class HalExtension implements Extension, LinkParser
{

    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $container->track($this);
    }

    /**
     * @return Link[]
     */
    public function parseLinks(ResponseInterface $response, AttributeBag $dataSet): \Traversable
    {
        // TODO: Implement parseLinks() method.
    }
}