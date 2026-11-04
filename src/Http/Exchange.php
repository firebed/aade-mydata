<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Throwable;

/**
 * One request to myDATA and its outcome, handed to the Connection::afterSending() listeners:
 * the response XML when the request got one, else the exception it failed with.
 */
final readonly class Exchange
{
    /**
     * @param  'GET'|'POST'  $method
     * @param  array<string, mixed>  $query
     * @param  string|null  $requestBody  The request XML; null for requests sent as a query only (e.g. CancelInvoice)
     * @param  string|null  $responseBody  The response XML; null when the request failed
     * @param  float  $duration  Seconds from sending the request to its outcome, retries and waits included
     */
    public function __construct(
        public MyDataRequest $request,
        public string $method,
        public string $url,
        public array $query,
        public ?string $requestBody,
        public ?string $responseBody,
        public ?Throwable $exception,
        public float $duration,
    ) {}

    /**
     * The myDATA action, e.g. 'SendInvoices'.
     */
    public function action(): string
    {
        return $this->request->action();
    }

    /**
     * Whether myDATA answered. A response can still carry myDATA's errors (e.g. a ResponseDoc
     * with a ValidationError per invoice): read them from the response.
     */
    public function successful(): bool
    {
        return $this->exception === null;
    }

    /**
     * Whether myDATA throttled the key (RateLimitExceededException).
     */
    public function rateLimited(): bool
    {
        return $this->exception instanceof RateLimitExceededException;
    }
}
