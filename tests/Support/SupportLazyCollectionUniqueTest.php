<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionUniqueTest extends TestCase
{
    public function testNonStrictUniqueMatchesCollectionForMixedScalarValues()
    {
        $items = [0, false, null, '', '0', 1];

        $this->assertSame(
            (new Collection($items))->unique()->all(),
            (new LazyCollection($items))->unique()->all(),
        );
    }
}
