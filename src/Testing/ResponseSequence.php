<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Testing;

use Closure;
use LogicException;

/**
 * Fake responses handed out one per request, in order (FakeGateway::sequence()).
 */
final class ResponseSequence
{
    /** @var (Closure(): mixed)|null */
    private ?Closure $fallback = null;

    /**
     * @param  list<mixed>  $responses  Anything FakeGateway accepts as a response
     */
    public function __construct(private array $responses) {}

    /**
     * The response for every request after the sequence ran out, instead of failing.
     */
    public function whenEmpty(mixed $response): self
    {
        $this->fallback = fn () => $response;

        return $this;
    }

    public function next(): mixed
    {
        if ($this->responses !== []) {
            return array_shift($this->responses);
        }

        return ($this->fallback ?? throw new LogicException('The fake response sequence ran out of responses.'))();
    }
}
