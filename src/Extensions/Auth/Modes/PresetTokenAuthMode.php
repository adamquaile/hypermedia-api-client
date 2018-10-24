<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Auth\Modes;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\HttpEvents;
use AdamQuaile\HypermediaApiClient\Protocols\Http\Events\PrepareRequestEvent;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Auth\AuthExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Auth\Events\UnauthorisedEvent;
use AdamQuaile\HypermediaApiClient\ServiceContainer;
use Http\Message\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class PresetTokenAuthMode implements Extension
{
    /**
     * @var RequestMatcher
     */
    private $requestMatcher;
    /**
     * @var string
     */
    private $queryStringParam;
    /**
     * @var string
     */
    private $headerName;
    /**
     * @var string
     */
    private $headerPattern;
    /**
     * @var string
     */
    private $token;

    private function __construct(
        RequestMatcher $requestMatcher,
        string $token,
        ?string $queryStringParam,
        ?string $headerName,
        ?string $headerPattern
    ) {
        $this->requestMatcher = $requestMatcher;
        $this->queryStringParam = $queryStringParam;
        $this->headerName = $headerName;
        $this->headerPattern = $headerPattern;
        $this->token = $token;
    }

    public static function inQueryString(RequestMatcher $requestMatcher, string $token, string $queryStringParam)
    {
        return new self($requestMatcher, $token, $queryStringParam, null, null);
    }

    public static function inHeader(RequestMatcher $requestMatcher, string $token, string $header = 'Authorization', string $headerPattern = 'Bearer %s')
    {
        return new self($requestMatcher, $token, null, $header, $headerPattern);
    }

    public function initialise(ApiClient $client, EventDispatcher $eventDispatcher, ServiceContainer $container)
    {
        $eventDispatcher = $eventDispatcher;
        $eventDispatcher->register(
            HttpEvents::PREPARE_REQUEST,
            function (PrepareRequestEvent $event) {
                $request = $event->getRequest();

                if ($this->requestMatcher->matches($request)) {
                    $event->setRequest($this->amendRequest($request));
                }
            }
        );
    }

    private function amendRequest(RequestInterface $request)
    {
        if ($this->headerName) {
            return $request->withHeader($this->headerName, sprintf($this->headerPattern, $this->token));
        }

        if ($this->queryStringParam) {
            return $request->withUri(
                $request->getUri()->withQuery(http_build_query([$this->queryStringParam => $this->token]))
            );
        }
    }
}