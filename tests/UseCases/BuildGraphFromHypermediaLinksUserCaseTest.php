<?php

namespace AdamQuaile\HypermediaApiClient\Tests\UseCases;

use AdamQuaile\HypermediaApiClient\Extensions\Formats\JsonApi\JsonApiExtension;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Tests\BaseTest;

class BuildGraphFromHypermediaLinksUserCaseTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
        $this->sut->addExtension(new JsonApiExtension());
    }

    public function test_following_links_multiple_times()
    {
        $this->assertEquals(
            ['1984', 'Animal Farm'],
            $this->sut->loadFromUri('https://api.bookstore.example.com/books/1984')
                ->author()
                ->booksWritten()
                ->map(
                    function(Resource $book) {
                        return $book->title;
                    }
                )
        );


    }

}