<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\Events;

use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class DeserialiseResponseBodyEvent
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var StreamInterface
     */
    private $responseStream;
    /**
     * @var AttributeBag
     */
    private $attributes;

    public function __construct(ResponseInterface $response, StreamInterface $responseStream, AttributeBag $data)
    {
        $this->response = $response;
        $this->responseStream = $responseStream;
        $this->attributes = $data;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getResponseStream(): StreamInterface
    {
        return $this->responseStream;
    }

    public function getAttributes(): AttributeBag
    {
        return $this->attributes;
    }
}