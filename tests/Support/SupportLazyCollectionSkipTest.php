<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionSkipTest extends TestCase
{
    public function testNegativeSkipMatchesCollectionWhenCountIsWithinCollectionSize()
    {
        $items = [2 => 'a', 5 => 'b', 9 => 'c'];

        $this->assertSame(
            (new Collection($items))->skip(-2)->all(),
            (new LazyCollection($items))->skip(-2)->all(),
        );
    }

    public function testNegativeSkipMatchesCollectionWhenCountExceedsCollectionSize()
    {
        $items = ['a'];

        $this->assertSame(
            (new Collection($items))->skip(-2)->all(),
            (new LazyCollection($items))->skip(-2)->all(),
        );
    }
}
