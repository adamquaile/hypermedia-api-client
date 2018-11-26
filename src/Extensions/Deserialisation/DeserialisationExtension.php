<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Deserialisation;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\ProcessResponseEvent;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\Events\DeserialiseResponseBodyEvent;
use AdamQuaile\HypermediaApiClient\ServiceContainer;

class DeserialisationExtension implements Extension
{
    const DESERIALISE = 'hypermedia.http.deserialisation.deserialise';

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $eventDispatcher->register(
            HttpEvents::PROCESS_RESPONSE,
            [$this, 'processResponse']
        );

        $this->dispatcher = $client->getEventDispatcher();
    }

    public function processResponse(ProcessResponseEvent $processResponseEvent)
    {
        $response   = $processResponseEvent->getResponse();
        $body       = $response->getBody();

        $deserialiseEvent = new DeserialiseResponseBodyEvent($response, $body, $processResponseEvent->getAttributeBag());
        $this->dispatcher->dispatch(self::DESERIALISE, $deserialiseEvent);
    }
}