<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionKeyByKeyNormalizationTest extends TestCase
{
    public function testKeyByNormalizesNumericStringKeysLikeCollection()
    {
        $items = [
            ['key' => '1', 'value' => 'first'],
            ['key' => '01', 'value' => 'second'],
        ];

        $eagerKeys = (new Collection($items))->keyBy('key')->keys()->all();
        $lazyKeys = (new LazyCollection($items))->keyBy('key')->keys()->all();

        $this->assertSame($eagerKeys, $lazyKeys);
    }
}
