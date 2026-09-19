<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;
use ValueError;

class SupportLazyCollectionCombineTest extends TestCase
{
    public function testCombineThrowsWhenThereAreMoreValuesThanKeys()
    {
        $this->expectException(ValueError::class);

        LazyCollection::make(['name'])
            ->combine(['Taylor', 'Otwell'])
            ->all();
    }

    public function testCombineThrowsWhenThereAreFewerValuesThanKeys()
    {
        $this->expectException(ValueError::class);

        LazyCollection::make(['name', 'age'])
            ->combine(['Taylor'])
            ->all();
    }
}
