<?php

namespace AdamQuaile\HypermediaApiClient\Protocols\Http;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\Model\Graph;
use AdamQuaile\HypermediaApiClient\Protocols\Protocol;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\FinaliseResourceEvent;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\PrepareRequestEvent;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\ProcessResponseEvent;
use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use Http\Client\HttpAsyncClient;
use Http\Message\RequestFactory;

class HttpProtocol implements Protocol
{
    /**
     * @var HttpAsyncClient
     */
    private $client;
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    public function __construct(HttpAsyncClient $client, RequestFactory $requestFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    public function supportsProtocolString(string $protocol): bool
    {
        return in_array($protocol, ['http', 'https']);
    }


    public function loadFromUri(string $uri, ApiClient $client): Resource
    {
        $eventDispatcher = $client->getEventDispatcher();

        $request = $this->requestFactory->createRequest('GET', $uri, $headers = [], null);

        $prepareRequestEvent = new PrepareRequestEvent($request);
        $eventDispatcher->dispatch(HttpEvents::PREPARE_REQUEST, $prepareRequestEvent);
        $request = $prepareRequestEvent->getRequest();

        $promise = $this->client->sendAsyncRequest($request);
        $response = $promise->wait();

        $attributes = new AttributeBag();
        $attributes->set('raw', $response->getBody());

        $processResponseEvent = new ProcessResponseEvent($request, $response, $attributes, new Graph());
        $eventDispatcher->dispatch(HttpEvents::PROCESS_RESPONSE, $processResponseEvent);

        $resource = new Resource($client, $uri, $processResponseEvent->getAttributeBag(), $processResponseEvent->getGraph());

        $finaliseResourceEvent = new FinaliseResourceEvent($request, $response, $resource);
        $eventDispatcher->dispatch(HttpEvents::FINALISE_RESOURCE, $finaliseResourceEvent);

        return $finaliseResourceEvent->getResource();
    }
}