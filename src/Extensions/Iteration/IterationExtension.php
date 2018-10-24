<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Iteration;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\FinaliseResourceEvent;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\ProcessResponseEvent;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\ServiceContainer;

class IterationExtension implements Extension
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $iterationStrategies = $container->findInstancesOf(IterationStrategy::class);
        $eventDispatcher->register(
            HttpEvents::FINALISE_RESOURCE,
            function (FinaliseResourceEvent $event) use ($iterationStrategies) {
                foreach ($iterationStrategies as $strategy) {

                    $request    = $event->getRequest();
                    $response   = $event->getResponse();
                    $resource   = $event->getResource();

                    if ($strategy->supports($request, $response, $resource)) {
                        $resource->setIterator($strategy->getIterator($request, $response, $resource));
                        break;
                    }
                }
            }
        );
    }

}