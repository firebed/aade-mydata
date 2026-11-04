<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;

/**
 * A failed attempt that is about to be retried, handed to the Connection::beforeRetry() listeners.
 */
final readonly class Retry
{
    /**
     * @param  int  $attempt  The number of the retry about to happen: 1 for the first one
     * @param  int  $delay  Milliseconds the request waits before it is sent again
     * @param  MyDataException  $reason  What the failed attempt would have thrown
     */
    public function __construct(
        public MyDataRequest $request,
        public int $attempt,
        public int $delay,
        public MyDataException $reason,
    ) {}

    public function action(): string
    {
        return $this->request->action();
    }

    /**
     * Whether myDATA throttled the key, so the wait is the Retry-After it asked for.
     */
    public function rateLimited(): bool
    {
        return $this->reason instanceof RateLimitExceededException;
    }
}
