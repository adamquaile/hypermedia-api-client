<?php

namespace AdamQuaile\HypermediaApiClient\Tests\UseCases;

use AdamQuaile\HypermediaApiClient\Extensions\Auth\AuthExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Auth\Modes\PresetTokenAuthMode;
use AdamQuaile\HypermediaApiClient\Tests\BaseTest;
use Http\Message\RequestMatcher\RequestMatcher;

class AuthViaApiKeyUseCaseTest extends BaseTest
{
    public function test_api_key_is_added_to_all_requests_if_configured()
    {
        $this->api->addExtension(
            PresetTokenAuthMode::inHeader(
                new RequestMatcher('', 'api\.example\.com', ['GET', 'POST', 'PUT', 'DELETE'], 'https'),
                'some-token'
            )
        );

        $this->api->loadFromUri('https://api.example.com/test');

        $this->assertEquals('Bearer some-token', $this->httpMock->getLastRequest()->getHeaderLine('Authorization'));

    }
}