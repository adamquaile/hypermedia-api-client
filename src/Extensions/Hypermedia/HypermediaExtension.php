<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Hypermedia;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\ProcessResponseEvent;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use AdamQuaile\HypermediaApiClient\ServiceContainer;

class HypermediaExtension implements Extension
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $eventDispatcher->register(
            HttpEvents::PROCESS_RESPONSE,
            function(ProcessResponseEvent $event) use ($container) {
                /**
                 * @var LinkParser[] $parsers
                 */
                $parsers = $container->findInstancesOf(LinkParser::class);
                foreach ($parsers as $parser) {
                    foreach ($parser->parseLinks($event->getResponse(), $event->getAttributeBag()) as $link) {
                        $event->getGraph()->addEdge($link->getName(), $link);
                    }
                }
            }
        );
    }
}