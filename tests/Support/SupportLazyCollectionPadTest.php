<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionPadTest extends TestCase
{
    public function testPositivePadReindexesSparseNumericKeysLikeCollection()
    {
        $collection = LazyCollection::make([2 => 'a', 5 => 'b', 9 => 'c']);

        $this->assertSame([
            0 => 'a',
            1 => 'b',
            2 => 'c',
            3 => 'x',
            4 => 'x',
        ], $collection->pad(5, 'x')->all());
    }
}
