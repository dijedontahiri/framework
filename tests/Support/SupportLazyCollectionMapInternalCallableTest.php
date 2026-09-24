<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionMapInternalCallableTest extends TestCase
{
    public function testMapSupportsUnaryInternalCallables()
    {
        $items = ['first' => 'a b'];

        $expected = (new Collection($items))->map(rawurlencode(...))->all();
        $actual = (new LazyCollection($items))->map(rawurlencode(...))->all();

        $this->assertSame($expected, $actual);
    }
}
