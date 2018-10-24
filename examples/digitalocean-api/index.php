<?php

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\IterationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Json\JsonExtension;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Parsing\Traversers\JsonPathTraverser;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Parsers\ResponseBodyLinkParser;
use AdamQuaile\HypermediaApiClient\Protocols\Http\HttpProtocol;

require_once __DIR__.'/../../vendor/autoload.php';

$token = getenv('DIGITALOCEAN_API_TOKEN');
if (!is_string($token) || empty($token)) {
    echo "Environment variable DIGITALOCEAN_API_TOKEN not set or empty";
    exit(1);
}

$apiClient = new ApiClient(
    [
        new HttpProtocol(
            \Http\Adapter\Guzzle6\Client::createWithConfig([
                'timeout' => 5
            ]),
            new \Http\Message\MessageFactory\GuzzleMessageFactory()
        )
    ],
    new EventDispatcher()
);

$hypermediaPlugin = new \AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\HypermediaExtension();

$apiClient->addExtension(new \AdamQuaile\HypermediaApiClient\Extensions\Auth\AuthExtension());
$apiClient->addExtension(new \AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\DeserialisationExtension());
$apiClient->addExtension(new JsonExtension());
$apiClient->addExtension($hypermediaPlugin);

$doApiV2Matcher = new \Http\Message\RequestMatcher\RequestMatcher('v2', 'api\.digitalocean\.com', ['GET', 'POST', 'PUT', 'DELETE'], 'https');

// How to preempt auth when no hypermedia controls..
$apiClient->addExtension(
    \AdamQuaile\HypermediaApiClient\Extensions\Auth\Modes\PresetTokenAuthMode::inHeader($doApiV2Matcher, $token)
);

$apiClient->getServiceContainer()->track(
    new ResponseBodyLinkParser(
        new JsonPathTraverser('$.links.pages'),
        new JsonPathTraverser('$.'),
        ResponseBodyLinkParser::NAME_FROM_KEY
    )
);

$iterationPlugin = new IterationExtension();
$iterationPlugin->registerStrategy(
    new \AdamQuaile\HypermediaApiClient\Examples\DigitalOceanApiV2\Pagination\PaginatedResourceIterationStrategy()
);
$apiClient->addExtension($iterationPlugin);

$images = $apiClient->loadFromUri('https://api.digitalocean.com/v2/images');

foreach ($images as $image) {
    var_dump($image);
//    echo $size->getData()->get('slug');
//    $domain->records()->at(0)->delete();
//    $domain->get('records')->at(0)->delete();
}
exit;

$images->do('create', ['name' => 'example.com']);
$images->create(['name' => 'example.com']);