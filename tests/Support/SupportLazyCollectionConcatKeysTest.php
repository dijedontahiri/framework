<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionConcatKeysTest extends TestCase
{
    public function testConcatPreservesExistingStringKeys()
    {
        $collection = LazyCollection::make(['a' => 1, 'b' => 2]);

        $this->assertSame([
            'a' => 1,
            'b' => 2,
            0 => 'x',
            1 => 'y',
        ], $collection->concat(['x', 'y'])->all());
    }

    public function testConcatPreservesSparseNumericKeysAndAppendsAfterHighestKey()
    {
        $collection = LazyCollection::make([2 => 1, 5 => 2, 9 => 3]);

        $this->assertSame([
            2 => 1,
            5 => 2,
            9 => 3,
            10 => 'x',
            11 => 'y',
        ], $collection->concat(['x', 'y'])->all());
    }

    public function testConcatNormalizesGeneratorNumericStringKeysBeforeAppending()
    {
        $source = function () {
            yield '1' => 'a';
            yield '01' => 'b';
        };

        $eager = (new Collection($source()))->concat(['x'])->all();
        $lazy = (new LazyCollection($source))->concat(['x'])->all();

        $this->assertSame($eager, $lazy);
    }
}
