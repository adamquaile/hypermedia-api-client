<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use AdamQuaile\HypermediaApiClient\ServiceContainer;
use Psr\Http\Message\ResponseInterface;

class JsonApiExtension implements Extension
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $container->track($this);
        $container->track(new JsonApiIterationStrategy());
        $container->track(new JsonApiGraphBuilder());
        $container->track(new JsonApiLinkParser());
    }
}