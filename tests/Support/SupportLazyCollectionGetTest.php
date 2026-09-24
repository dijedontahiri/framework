<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SupportLazyCollectionGetTest extends TestCase
{
    public function testGetDoesNotLooselyMatchNumericStringKeys()
    {
        $collection = new LazyCollection(['01' => 'leading-zero']);

        $this->assertSame('fallback', $collection->get(1, 'fallback'));
        $this->assertSame('leading-zero', $collection->get('01', 'fallback'));
    }
}
