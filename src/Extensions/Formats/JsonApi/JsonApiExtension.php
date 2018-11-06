<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use AdamQuaile\HypermediaApiClient\Model\DataSet;
use AdamQuaile\HypermediaApiClient\ServiceContainer;
use Psr\Http\Message\ResponseInterface;

class JsonApiExtension implements Extension, LinkParser
{
    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $container->track($this);
        $container->track(new JsonApiIterationStrategy());
    }

    /**
     * @return Link[]
     */
    public function parseLinks(ResponseInterface $response, DataSet $dataSet): \Traversable
    {
        if ('application/vnd.api+json' !== $response->getHeaderLine('Content-type')) {
            return;
        }

        $deserialised = $dataSet->get('deserialised');

        if (is_null($deserialised)) {
            return;
        }
        if (isset($deserialised->data) && !is_object($deserialised->data)) {
            return;
        }
        if (!isset($deserialised->data)) {
            $deserialised->data = (object) [];
        }
        if (!isset($deserialised->data->links)) {
            $deserialised->data->links = [];
        }
        foreach ($deserialised->data->links as $name => $link) {
            if (is_string($link)) {
                yield new Link($link, $name);
            } else if (is_object($link) && isset($link->href)) {
                yield new Link($link->href, $name);
            }
        }
        if (!isset($deserialised->data->relationships)) {
            $deserialised->data->relationships = [];
        }
        foreach ($deserialised->data->relationships as $key => $relationship) {
            foreach ($relationship->links ?? [] as $name => $link) {
                if (is_string($link)) {
                    yield new Link($link, "$key.$name");
                } else if (is_object($link) && isset($link->href)) {
                    yield new Link($link->href, "$key.$name");
                }
            }
        }
    }
}