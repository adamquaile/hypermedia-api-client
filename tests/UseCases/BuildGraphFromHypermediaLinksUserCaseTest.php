<?php

namespace AdamQuaile\HypermediaApiClient\Tests\UseCases;

use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\DeserialisationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi\JsonApiExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\HypermediaExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\IterationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Json\JsonExtension;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Tests\BaseTest;
use Http\Message\RequestMatcher\RequestMatcher;

class BuildGraphFromHypermediaLinksUserCaseTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
        $this->sut->addExtension(new DeserialisationExtension());
        $this->sut->addExtension(new IterationExtension());
        $this->sut->addExtension(new JsonExtension());
        $this->sut->addExtension(new HypermediaExtension());
        $this->sut->addExtension(new JsonApiExtension());
    }

    public function test_following_links_multiple_times()
    {
        $this->httpMock->on(
            new RequestMatcher('/books/1984$', null, 'GET'),
            $this->messageFactory
                ->createResponse(200)
                ->withHeader('Content-type', 'application/vnd.api+json')
                ->withBody($this->streamFactory->createStream(
                    \json_encode(
                        [
                            'data' => [
                                'id' => '1984',
                                'attributes' => [
                                    'title' => '1984'
                                ],
                                'relationships' => [
                                    'author' => [
                                        'links' => [
                                            'self' => 'https://api.bookstore.example.com/authors/George-Orwell'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    )
                ))
        );
        $this->httpMock->on(
            new RequestMatcher('/books/Animal-Farm$', null, 'GET'),
            $this->messageFactory
                ->createResponse(200)
                ->withHeader('Content-type', 'application/vnd.api+json')
                ->withBody($this->streamFactory->createStream(
                    \json_encode(
                        [
                            'data' => [
                                'id' => 'Animal-Farm',
                                'attributes' => [
                                    'title' => 'Animal Farm'
                                ],
                                'relationships' => [
                                    'author' => [
                                        'links' => [
                                            'self' => 'https://api.bookstore.example.com/authors/George-Orwell'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    )
                ))
        );

        $this->httpMock->on(
            new RequestMatcher('/authors/George-Orwell$', null, 'GET'),
            $this->messageFactory
                ->createResponse(200)
                ->withHeader('Content-type', 'application/vnd.api+json')
                ->withBody($this->streamFactory->createStream(
                    \json_encode(
                        [
                            'data' => [
                                'id' => 'George-Orwell',
                                'attributes' => [
                                    'title' => 'George Orwell'
                                ],
                                'relationships' => [
                                    'booksWritten' => [
                                        'links' => [
                                            'self' => 'https://api.bookstore.example.com/authors/George-Orwell/books'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    )
                ))
        );
        
        $this->httpMock->on(
            new RequestMatcher('/authors/George-Orwell/books$', null, 'GET'),
            $this->messageFactory
                ->createResponse(200)
                ->withHeader('Content-type', 'application/vnd.api+json')
                ->withBody($this->streamFactory->createStream(
                    \json_encode(
                        [
                            'data' => [
                                [
                                    'id' => '1984',
                                    'attributes' => [
                                        'title' => '1984'
                                    ],
                                    'relationships' => [
                                        'author' => [
                                            'links' => [
                                                'self' => 'https://api.bookstore.example.com/authors/George-Orwell'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'id' => 'Animal-Farm',
                                    'attributes' => [
                                        'title' => 'Animal Farm'
                                    ],
                                    'relationships' => [
                                        'author' => [
                                            'links' => [
                                                'self' => 'https://api.bookstore.example.com/authors/George-Orwell'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    )
                ))
        );

        // This is currently possible...
        $this->assertEquals(
            ['1984', 'Animal Farm'],
            iterator_to_array(
                $this->sut->loadFromUri('https://api.bookstore.example.com/books/1984')
                    ->author()
                    ->booksWritten()
                    ->map(
                        function($book) {
                            return $book->attributes->title;
                        }
                    )
            )
        );
        return;

        // ...this is ideal, parsing objects from resources
        $this->assertEquals(
            ['1984', 'Animal Farm'],
            iterator_to_array(
                $this->sut->loadFromUri('https://api.bookstore.example.com/books/1984')
                    ->author()
                    ->booksWritten()
                    ->map(
                        function(Resource $book) {
                            return $book->title;
                        }
                    )
            )
        );


    }

}