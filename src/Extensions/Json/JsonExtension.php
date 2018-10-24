<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Json;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\DeserialisationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\Events\DeserialiseResponseBodyEvent;
use AdamQuaile\HypermediaApiClient\ServiceContainer;

class JsonExtension implements Extension
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $eventDispatcher->register(
            DeserialisationExtension::DESERIALISE,
            [$this, 'deserialise']
        );
    }

    public function deserialise(DeserialiseResponseBodyEvent $event)
    {
        $response = $event->getResponse();
        if (!$response->hasHeader('Content-type')) {
            return;
        }

        if (0 !== stripos($response->getHeaderLine('Content-type'), 'application/json')) {
            return;
        }

        $streamContents = $event->getResponseStream()->getContents();
        $event->getData()->set('deserialised', json_decode($streamContents));
    }
}