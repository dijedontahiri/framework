<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportCollectionCollapseWithKeysEnumerableTest extends TestCase
{
    public function testCollapseWithKeysAcceptsNestedLazyCollections()
    {
        $collection = new Collection([
            new LazyCollection(['a' => 1]),
            new Collection(['b' => 2]),
        ]);

        $this->assertSame(['a' => 1, 'b' => 2], $collection->collapseWithKeys()->all());
    }
}
