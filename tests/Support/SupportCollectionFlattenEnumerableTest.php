<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportCollectionFlattenEnumerableTest extends TestCase
{
    public function testCollectionFlattenFlattensNestedLazyCollections()
    {
        $collection = new Collection([
            new LazyCollection(['#foo', ['#bar']]),
            ['#baz'],
        ]);

        $this->assertSame(['#foo', '#bar', '#baz'], $collection->flatten()->all());
    }
}
