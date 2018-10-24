<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Auth\Events;

use AdamQuaile\HypermediaApiClient\Model\DataSet;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UnauthorisedEvent
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var DataSet
     */
    private $dataSet;

    public function __construct(RequestInterface $request, ResponseInterface $response, DataSet $dataSet)
    {
        $this->request = $request;
        $this->response = $response;
        $this->dataSet = $dataSet;
    }
}