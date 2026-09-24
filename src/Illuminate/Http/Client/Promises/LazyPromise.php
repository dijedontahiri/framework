<?php

namespace Illuminate\Http\Client\Promises;

use Closure;
use GuzzleHttp\Promise\PromiseInterface;
use RuntimeException;

class LazyPromise implements PromiseInterface
{
    /**
     * The callbacks to execute after the Guzzle Promise has been built.
     *
     * @var list<callable>
     */
    protected array $pending = [];

    /**
     * The promise built by the creator.
     *
     * @var \GuzzleHttp\Promise\PromiseInterface
     */
    protected PromiseInterface $guzzlePromise;

    /**
     * Create a new lazy promise instance.
     *
     * @param  (\Closure(): \GuzzleHttp\Promise\PromiseInterface)  $promiseBuilder  The callback to build a new PromiseInterface.
     */
    public function __construct(protected Closure $promiseBuilder)
    {
    }

    /**
     * Build the promise from the promise builder.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     *
     * @throws \RuntimeException If the promise has already been built
     */
    public function buildPromise(): PromiseInterface
    {
        if (! $this->promiseNeedsBuilt()) {
            throw new RuntimeException('Promise already built');
        }

        return $this->setGuzzlePromise(call_user_func($this->promiseBuilder));
    }

    #[\Override]
    public function then(?callable $onFulfilled = null, ?callable $onRejected = null): PromiseInterface
    {
        return $this->chain(
            fn (PromiseInterface $promise) => $promise->then($onFulfilled, $onRejected)
        );
    }

    #[\Override]
    public function otherwise(callable $onRejected): PromiseInterface
    {
        return $this->chain(
            fn (PromiseInterface $promise) => $promise->otherwise($onRejected)
        );
    }

    #[\Override]
    public function getState(): string
    {
        if ($this->promiseNeedsBuilt()) {
            return PromiseInterface::PENDING;
        }

        return $this->guzzlePromise->getState();
    }

    #[\Override]
    public function resolve($value = null): void
    {
        throw new \LogicException('Cannot resolve a lazy promise.');
    }

    #[\Override]
    public function reject($reason): void
    {
        throw new \LogicException('Cannot reject a lazy promise.');
    }

    #[\Override]
    public function cancel(): void
    {
        throw new \LogicException('Cannot cancel a lazy promise.');
    }

    #[\Override]
    public function wait(bool $unwrap = true)
    {
        if ($this->promiseNeedsBuilt()) {
            $this->buildPromise();
        }

        return $this->guzzlePromise->wait($unwrap);
    }

    /**
     * Determine if the promise has been created from the promise builder.
     *
     * @return bool
     */
    public function promiseNeedsBuilt(): bool
    {
        return ! isset($this->guzzlePromise);
    }

    /**
     * Create an independent promise chained from this promise.
     *
     * @param  \Closure(\GuzzleHttp\Promise\PromiseInterface): \GuzzleHttp\Promise\PromiseInterface  $callback
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function chain(Closure $callback): PromiseInterface
    {
        $nextPromise = null;

        $nextPromise = new static(function () use (&$nextPromise) {
            if ($this->promiseNeedsBuilt()) {
                $this->buildPromise();
            }

            return $nextPromise->guzzlePromise;
        });

        $pendingCallback = static function (PromiseInterface $promise) use ($callback, $nextPromise) {
            $nextPromise->setGuzzlePromise($callback($promise));
        };

        if ($this->promiseNeedsBuilt()) {
            $this->pending[] = $pendingCallback;
        } else {
            $pendingCallback($this->guzzlePromise);
        }

        return $nextPromise;
    }

    /**
     * Set the built Guzzle promise and attach pending child promises.
     *
     * @param  \GuzzleHttp\Promise\PromiseInterface  $promise
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function setGuzzlePromise(PromiseInterface $promise): PromiseInterface
    {
        $this->guzzlePromise = $promise;

        foreach ($this->pending as $pendingCallback) {
            $pendingCallback($this->guzzlePromise);
        }

        $this->pending = [];

        return $this->guzzlePromise;
    }
}
