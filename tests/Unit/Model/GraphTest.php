<?php

namespace AdamQuaile\HypermediaApiClient\Tests\Unit\Model;

use AdamQuaile\HypermediaApiClient\Model\Graph;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{
    public function test_graph()
    {
        $sut = new Graph();

        $node = new Graph();
        $sut->addEdge('field', $node);
        $this->assertTrue($sut->hasEdge('field'));
        $this->assertSame($node, $sut->getEdge('field'));
        $this->assertSame($node, $sut->field);
    }
}