<?php

namespace AdamQuaile\HypermediaApiClient\Tests\Unit\Model;

use AdamQuaile\HypermediaApiClient\Model\AttributeBag;
use PHPUnit\Framework\TestCase;

class AttributeBagTest extends TestCase
{
    public function test_bag_returns_values_saved()
    {
        $sut = new AttributeBag();
        $this->assertFalse($sut->has('raw'));

        $sut->set('raw', '{"key": "value"}');

        $this->assertTrue($sut->has('raw'));
        $this->assertSame('{"key": "value"}', $sut->get('raw'));
    }
}