<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi;

use AdamQuaile\HypermediaApiClient\Extensions\GraphBuilding\GraphBuilder;
use AdamQuaile\HypermediaApiClient\Model\Graph;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class JsonApiGraphBuilder implements GraphBuilder
{

    public function supports(Resource $resource, ResponseInterface $response, RequestInterface $request): bool
    {
        return
            'application/vnd.api+json' === $response->getHeaderLine('Content-type') &&
            is_object($resource->getData()->get('deserialised')) &&
            \is_array($resource->getData()->get('deserialised')->data);

    }

    public function buildGraph($data, Graph $graph): Graph
    {
        $primaryData = $data;

        if (isset($data->data)) {
            $primaryData = $data->data;
        }

        if (is_array($primaryData)) {
            foreach ($data->data as $i => $object) {
                $graph->addEdge($i, $this->buildGraph($object, new Graph()));
            }

            return $graph;
        }

        foreach ($primaryData->attributes ?? [] as $key => $value) {
            $graph->addEdge($key, $value);
        }

        return $graph;
    }
}