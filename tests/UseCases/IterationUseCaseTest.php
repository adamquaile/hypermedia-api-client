<?php

namespace AdamQuaile\HypermediaApiClient\Tests\UseCases;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Examples\DigitalOceanApiV2\Pagination\PaginatedResourceIterationStrategy;
use AdamQuaile\HypermediaApiClient\Extensions\Auth\AuthExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\DeserialisationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\HypermediaExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Parsers\ResponseBodyLinkParser;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\IterationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Json\JsonExtension;
use AdamQuaile\HypermediaApiClient\Parsing\Traversers\JsonPathTraverser;
use AdamQuaile\HypermediaApiClient\Protocols\Http\HttpProtocol;
use AdamQuaile\HypermediaApiClient\Tests\BaseTest;
use Http\Message\MessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\StreamFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class IterationUseCaseTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        $this->api->getServiceContainer()->track(
            new ResponseBodyLinkParser(
                new JsonPathTraverser('$.links.pages'),
                new JsonPathTraverser('$.'),
                ResponseBodyLinkParser::NAME_FROM_KEY
            )
        );

        $this->api->getServiceContainer()->track(new PaginatedResourceIterationStrategy());
    }


    public function test_paginated_response_can_be_iterated()
    {
        $initialPageResponseBody = <<<JSON
{
  "images": [
    {
      "id": 7555620,
      "name": "Nifty New Snapshot",
      "distribution": "Ubuntu",
      "slug": null,
      "public": false,
      "regions": [
        "nyc2",
        "nyc2"
      ],
      "created_at": "2014-11-04T22:23:02Z",
      "type": "snapshot",
      "min_disk_size": 20,
      "size_gigabytes": 2.34,
      "description": "",
      "tags": [

      ],
      "status": "available",
      "error_message": ""
    }
  ],
  "links": {
    "pages": {
      "last": "https://api.digitalocean.com/v2/images?page=26&per_page=1",
      "next": "https://api.digitalocean.com/v2/images?page=2&per_page=1"
    }
  },
  "meta": {
    "total": 2
  }
}
JSON;

        $secondPageResponseBody = <<<JSON
{
  "images": [
    {
      "id": 7555621,
      "name": "Nifty Second Snapshot",
      "distribution": "Ubuntu",
      "slug": null,
      "public": false,
      "regions": [
        "nyc2",
        "nyc2"
      ],
      "created_at": "2014-11-04T22:23:02Z",
      "type": "snapshot",
      "min_disk_size": 20,
      "size_gigabytes": 2.34,
      "description": "",
      "tags": [

      ],
      "status": "available",
      "error_message": ""
    }
  ],
  "links": {
    "pages": {
      "first": "https://api.digitalocean.com/v2/images?page=16&per_page=1",
      "prev": "https://api.digitalocean.com/v2/images?page=1&per_page=1"
    }
  },
  "meta": {
    "total": 2
  }
}
JSON;

        $initialPageResponse = $this->messageFactory
            ->createResponse(200)
            ->withHeader('Content-type', 'application/json')
            ->withBody($this->streamFactory->createStream($initialPageResponseBody));
        $secondPageResponse = $this->messageFactory
            ->createResponse(200)
            ->withHeader('Content-type', 'application/json')
            ->withBody($this->streamFactory->createStream($secondPageResponseBody));
        $this->httpMock->addResponse($initialPageResponse);
        $this->httpMock->addResponse($secondPageResponse);
        $images = $this->api->loadFromUri('https://api.digitalocean.com/v2/images');

        $names = [];
        foreach ($images as $image) {
            $names[] = $image->name;
        }
        $this->assertSame(['Nifty New Snapshot', 'Nifty Second Snapshot'], $names);

    }
}