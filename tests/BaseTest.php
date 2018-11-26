<?php

namespace AdamQuaile\HypermediaApiClient\Tests;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\DeserialisationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Json\JsonExtension;
use AdamQuaile\HypermediaApiClient\Protocols\Http\HttpProtocol;
use Http\Message\MessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\StreamFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * @var ApiClient
     */
    protected $api;

    /**
     * @var Client
     */
    protected $httpMock;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var StreamFactory
     */
    protected $streamFactory;

    public function setUp()
    {
        $this->messageFactory = new GuzzleMessageFactory();
        $this->streamFactory = new GuzzleStreamFactory();
        $this->httpMock = new MockClient();
        $this->httpMock = new Client();
        $this->api = new ApiClient(
            [
                new HttpProtocol(
                    $this->httpMock,
                    new \Http\Message\MessageFactory\GuzzleMessageFactory()
                )
            ],
            new EventDispatcher()
        );
    }

}