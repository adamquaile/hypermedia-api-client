<?php

namespace AdamQuaile\HypermediaApiClient\Protocols\Http\Events;

use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use AdamQuaile\HypermediaApiClient\Model\Graph;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProcessResponseEvent
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var AttributeBag
     */
    private $data;
    /**
     * @var Graph
     */
    private $graph;
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request, ResponseInterface $response, AttributeBag $data, Graph $graph)
    {
        $this->request = $request;
        $this->response = $response;
        $this->data = $data;
        $this->graph = $graph;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return AttributeBag
     */
    public function getAttributeBag(): AttributeBag
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getGraph(): Graph
    {
        return $this->graph;
    }

    public function setGraph(Graph $graph): void
    {
        $this->graph = $graph;
    }
}