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
        $doApiV2Matcher = new RequestMatcher('', 'api\.example\.com', ['GET', 'POST', 'PUT', 'DELETE'], 'https');
        $token = 'some-token';

        $this->sut->addExtension(new AuthExtension());
        $this->sut->addExtension(PresetTokenAuthMode::inHeader($doApiV2Matcher, $token));

        $this->sut->loadFromUri('https://api.example.com/test');

        $this->assertEquals('Bearer some-token', $this->httpMock->getLastRequest()->getHeaderLine('Authorization'));

    }
}