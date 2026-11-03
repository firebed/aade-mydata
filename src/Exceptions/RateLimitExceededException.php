<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Exceptions;

use Throwable;

/**
 * myDATA throttled the subscription key (HTTP 429). myDATA counts the calls of a
 * subscription key over a window of about five minutes and refuses every further
 * call until the window ends, telling how long to wait in the Retry-After header
 * and in the message ("Rate limit is exceeded. Try again in 298 seconds.").
 */
class RateLimitExceededException extends TransmissionFailedException
{
    /**
     * @param  int|null  $retryAfter  Seconds to wait before myDATA accepts calls again, when known
     */
    public function __construct(?string $message = null, int $code = 429, ?Throwable $previous = null, private readonly ?int $retryAfter = null)
    {
        parent::__construct($message ?: 'myDATA rate limit exceeded.', $code, $previous);
    }

    /**
     * Seconds to wait before myDATA accepts calls from this subscription key again, null when not told.
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }

    /**
     * The Unix timestamp from which calls are accepted again, null when not told.
     */
    public function getRetryAt(): ?int
    {
        return $this->retryAfter === null ? null : time() + $this->retryAfter;
    }

    /**
     * The wait myDATA asks for: the Retry-After header, else the seconds or minutes in the message.
     */
    public static function parseRetryAfter(?string $header, string $body): ?int
    {
        if ($header !== null && is_numeric(trim($header))) {
            return (int) ceil((float) trim($header));
        }

        if (preg_match('/try again in (\d+) (second|minute)/i', $body, $m) === 1) {
            return (int) $m[1] * (strtolower($m[2]) === 'minute' ? 60 : 1);
        }

        return null;
    }
}
