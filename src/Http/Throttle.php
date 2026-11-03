<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

/**
 * Keeps a minimum spacing between the requests of a connection. The clock is shared
 * by every connection derived from the one that created it (Connection::withThrottle()),
 * so re-deriving a connection between two calls does not reset the spacing.
 */
final class Throttle
{
    private ?float $nextSendAt = null;

    /**
     * @param  int  $milliseconds  Minimum spacing between two requests
     */
    public function __construct(public readonly int $milliseconds) {}

    /**
     * Reserves the next slot and returns how many milliseconds to wait before using it.
     */
    public function pause(): int
    {
        $now = microtime(true);
        $wait = $this->nextSendAt === null ? 0 : max(0, (int) round(($this->nextSendAt - $now) * 1000));

        $this->nextSendAt = max($now, $this->nextSendAt ?? $now) + $this->milliseconds / 1000;

        return $wait;
    }
}
