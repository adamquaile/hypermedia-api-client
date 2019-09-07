<?php

namespace AdamQuaile\HypermediaApiClient\Tests;

use Http\Client\Exception;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Client\Promise\HttpFulfilledPromise;
use Http\Client\Promise\HttpRejectedPromise;
use Http\Message\RequestMatcher;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MockClient implements HttpClient, HttpAsyncClient
{
    private $conditionalResponses = [];

    public function addResponse(RequestMatcher $requestMatcher, ResponseInterface $response)
    {
        $this->conditionalResponses[] = [
            'matcher' => $requestMatcher,
            'result' => $response
        ];
    }
    public function addException(RequestMatcher $requestMatcher, \Exception $exception)
    {
        $this->conditionalResponses[] = [
            'matcher' => $requestMatcher,
            'result' => $exception
        ];
    }

    /**
     * Sends a PSR-7 request.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Http\Client\Exception If an error happens during processing the request.
     * @throws \Exception             If processing the request is impossible (eg. bad configuration).
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->conditionalResponses as ['matcher' => $matcher, 'result' => $result]) {
            /**
             * @var RequestMatcher $matcher
             */
            if ($matcher->matches($request)) {
                if ($result instanceof ResponseInterface) {
                    return $result;
                }
                if ($result instanceof \Exception) {
                    throw $result;
                }

                throw new \LogicException("Stubbed result is neither a response nor an exception");
            }
        }

        throw new \LogicException("No stubbed responses for request {$request->getMethod()} {$request->getUri()}");
    }

    /**
     * Sends a PSR-7 request in an asynchronous way.
     *
     * Exceptions related to processing the request are available from the returned Promise.
     *
     * @param RequestInterface $request
     *
     * @return Promise Resolves a PSR-7 Response or fails with an Http\Client\Exception.
     *
     * @throws \Exception If processing the request is impossible (eg. bad configuration).
     */
    public function sendAsyncRequest(RequestInterface $request)
    {
        $result = $this->sendRequest($request);
        if ($result instanceof ResponseInterface) {
            return new HttpFulfilledPromise($result);
        }

        if ($result instanceof Exception) {
            return new HttpRejectedPromise($result);
        }

        throw new \LogicException("Neither response nor exception returned");
    }
}