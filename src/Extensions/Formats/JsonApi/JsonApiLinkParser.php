<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi;

use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use Psr\Http\Message\ResponseInterface;

class JsonApiLinkParser implements LinkParser
{

    /**
     * @return Link[]
     */
    public function parseLinks(ResponseInterface $response, AttributeBag $dataSet): \Traversable
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