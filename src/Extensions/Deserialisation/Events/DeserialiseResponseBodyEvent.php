<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\Events;

use AdamQuaile\HypermediaApiClient\Model\DataSet;
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
     * @var DataSet
     */
    private $data;

    public function __construct(ResponseInterface $response, StreamInterface $responseStream, DataSet $data)
    {
        $this->response = $response;
        $this->responseStream = $responseStream;
        $this->data = $data;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getResponseStream(): StreamInterface
    {
        return $this->responseStream;
    }

    public function getData(): DataSet
    {
        return $this->data;
    }
}