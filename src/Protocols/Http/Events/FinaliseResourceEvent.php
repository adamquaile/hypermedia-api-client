<?php

namespace AdamQuaile\HypermediaApiClient\Protocols\Http\Events;

use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FinaliseResourceEvent
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var Resource
     */
    private $resource;

    public function __construct(RequestInterface $request, ResponseInterface $response, Resource $resource)
    {
        $this->request = $request;
        $this->response = $response;
        $this->resource = $resource;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return Resource
     */
    public function getResource(): Resource
    {
        return $this->resource;
    }
}