<?php

require_once __DIR__.'/vendor/autoload.php';

$apiClient = new \AdamQuaile\HypermediaApiClient\ApiClient();

$apiClient->setClient(new GuzzleHttpClient);
$apiClient->setClient(
    new ChainedClientLoader([
        $loader1,
        $loader2
    ])
);


$apiClient->loadFromUri('https://httpbin.org');

$image = $apiClient->images(['sort' => ['createdAt' => 'DESC']])->at(0);

$dimensions = [$image->size->width, $image->size->height];

$contents = $image->alternateRepresentation('image/png', 'image/*');


$pcApi = new \AdamQuaile\HypermediaApiClient\ApiClient();
$pcApi->setClient(new GuzzleClient());
