<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Auth\Events;

use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
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
     * @var AttributeBag
     */
    private $dataSet;

    public function __construct(RequestInterface $request, ResponseInterface $response, AttributeBag $dataSet)
    {
        $this->request = $request;
        $this->response = $response;
        $this->dataSet = $dataSet;
    }
}