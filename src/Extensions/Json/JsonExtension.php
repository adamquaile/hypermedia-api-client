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

        if (false === stripos($response->getHeaderLine('Content-type'), 'json')) {
            return;
        }

        $decoded = \json_decode($event->getAttributes()->get('raw'));
        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new \LogicException("Resource treated as JSON, but got syntax error: " . \json_last_error_msg());
        }
        $event->getAttributes()->set('deserialised', $decoded);
    }
}