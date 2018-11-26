<?php

namespace AdamQuaile\HypermediaApiClient\Tests\Unit\Model;

use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use AdamQuaile\HypermediaApiClient\Model\Graph;
use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Tests\BaseTest;
use PHPUnit\Framework\TestCase;

class ResourceTest extends BaseTest
{
    public function test_resource_has_magic_field_accessor()
    {
        $attributes = new AttributeBag();
        $attributes->set('field', 'value');
        $graph = new Graph();
        $graph->addEdge('field', 'value');
        $resource = new Resource($this->api, 'https://example.com', $attributes, $graph);
        $this->assertSame('value', $resource->field());
    }

    public function test_resource_can_provide_links()
    {
        $attributes = new AttributeBag();
        $attributes->set('field', 'value');
        $graph = new Graph();
        $graph->addEdge('link', new Link('https://example.com/link', 'link'));
        $resource = new Resource($this->api, 'https://example.com', $attributes, $graph);
        $this->assertTrue($resource->hasLink('link'));
    }

}