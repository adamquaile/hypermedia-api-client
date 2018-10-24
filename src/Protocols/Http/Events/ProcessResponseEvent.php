<?php

namespace AdamQuaile\HypermediaApiClient\Protocols\Http\Events;

use AdamQuaile\HypermediaApiClient\Model\DataSet;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProcessResponseEvent
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var DataSet
     */
    private $data;
    /**
     * @var array
     */
    private $links;
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request, ResponseInterface $response, DataSet $data, $links)
    {
        $this->request = $request;
        $this->response = $response;
        $this->data = $data;
        $this->links = $links;
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
     * @return DataSet
     */
    public function getData(): DataSet
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links): void
    {
        $this->links = $links;
    }
}