<?php

namespace Illuminate\Tests\Http;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use PHPUnit\Framework\TestCase;

class HttpClientAsyncPromiseTest extends TestCase
{
    public function testAsyncPromiseChainsReturnIndependentPromises()
    {
        $factory = new Factory;

        $factory->fake([
            '*' => $factory::response('', 200),
        ]);

        $responsePromise = $factory->async()->get('https://laravel.test');
        $statusPromise = $responsePromise->then(fn (Response $response) => $response->status());
        $onePromise = $statusPromise->then(fn () => 'one');
        $twoPromise = $onePromise->then(fn () => 'two');

        $this->assertNotSame($responsePromise, $statusPromise);
        $this->assertNotSame($statusPromise, $onePromise);
        $this->assertNotSame($onePromise, $twoPromise);

        $this->assertInstanceOf(Response::class, $responsePromise->wait());
        $this->assertSame(200, $statusPromise->wait());
        $this->assertSame('one', $onePromise->wait());
        $this->assertSame('two', $twoPromise->wait());

        $factory->assertSentCount(1);
    }
}
