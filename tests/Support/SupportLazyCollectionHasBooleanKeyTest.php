<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionHasBooleanKeyTest extends TestCase
{
    public function testHasNormalizesBooleanKeysLikePhpArrays()
    {
        $collection = new LazyCollection(['unrelated' => 'value']);

        $this->assertFalse($collection->has(true));
        $this->assertFalse($collection->has(false));

        $collection = new LazyCollection([
            0 => 'false-key',
            1 => 'true-key',
        ]);

        $this->assertTrue($collection->has(true));
        $this->assertTrue($collection->has(false));
        $this->assertTrue($collection->has(true, false));
    }

    public function testHasAnyNormalizesBooleanKeysLikePhpArrays()
    {
        $collection = new LazyCollection(['unrelated' => 'value']);

        $this->assertFalse($collection->hasAny(true));
        $this->assertFalse($collection->hasAny(false));

        $this->assertTrue((new LazyCollection([1 => 'true-key']))->hasAny(true));
        $this->assertTrue((new LazyCollection([0 => 'false-key']))->hasAny(false));
    }
}
