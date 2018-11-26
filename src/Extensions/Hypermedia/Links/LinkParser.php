<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links;

use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use Psr\Http\Message\ResponseInterface;

interface LinkParser
{
    /**
     * @return Link[]
     */
    public function parseLinks(ResponseInterface $response, AttributeBag $dataSet): \Traversable;
}