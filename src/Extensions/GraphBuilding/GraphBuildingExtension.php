<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\GraphBuilding;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\FinaliseResourceEvent;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\ServiceContainer;

class GraphBuildingExtension implements Extension
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $eventDispatcher->register(
            HttpEvents::FINALISE_RESOURCE,
            function (FinaliseResourceEvent $event) use ($container) {

                /**
                 * @var GraphBuilder[] $graphBuilders
                 */
                $graphBuilders = $container->findInstancesOf(GraphBuilder::class);
                $resource = $event->getResource();
                $graph = $resource->getGraph();

                foreach ($graphBuilders as $builder) {
                    $graph = $builder->buildGraph($resource->getData()->get('deserialised'), $resource->getGraph());
                }

                $resource->setGraph($graph);
            }
        );
    }
}