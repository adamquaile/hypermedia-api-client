<?php

require_once __DIR__.'/../../vendor/autoload.php';

$apiClient = new \AdamQuaile\HypermediaApiClient\ApiClient();
$apiClient->setClientLoader(
    new \AdamQuaile\HypermediaApiClient\Protocols\Loaders\ChainedClientLoader(
        [
            new FtpClient
        ]
    )
);

$root = $apiClient->loadFromUri('ftp://example.com');
$root->get('ls');

$root->cd('subdir')->ls()