<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Auth;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\ProcessResponseEvent;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Auth\Events\UnauthorisedEvent;
use AdamQuaile\HypermediaApiClient\ServiceContainer;

class AuthExtension implements Extension
{
    const UNAUTHORISED = 'hypermedia.http.auth.unauthorised';
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $this->dispatcher = $eventDispatcher;
        $this->dispatcher->register(HttpEvents::PROCESS_RESPONSE, [$this, 'processResponse']);
    }

    public function processResponse(ProcessResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response->getStatusCode() === 401) {

            $unauthorisedEvent = new UnauthorisedEvent($event->getRequest(), $event->getResponse(), $event->getAttributeBag());
            $this->dispatcher->dispatch(self::UNAUTHORISED, $unauthorisedEvent);
        }
    }
}