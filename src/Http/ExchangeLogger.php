<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Psr\Log\LoggerInterface;

/**
 * Writes the requests of a connection to a PSR-3 logger (Connection::withLogger()):
 * an answered request at info, a retry or a throttled request at warning, any other
 * failure at error. The credentials are never logged; the request and response XML and
 * the VAT numbers of the query (entityVatNumber, receiverVatNumber, …) only when asked for.
 */
final readonly class ExchangeLogger
{
    public function __construct(private LoggerInterface $logger, private bool $withBodies = false) {}

    public function sent(Exchange $exchange): void
    {
        $milliseconds = (int) round($exchange->duration * 1000);
        $action = $exchange->action();

        $context = [
            'action' => $action,
            'method' => $exchange->method,
            'url' => $exchange->url,
            'query' => $this->withBodies ? $exchange->query : array_filter($exchange->query, fn (string $key) => ! str_ends_with($key, 'VatNumber'), ARRAY_FILTER_USE_KEY),
            'duration_ms' => $milliseconds,
        ];

        if ($this->withBodies) {
            $context['request_body'] = $exchange->requestBody;
            $context['response_body'] = $exchange->responseBody;
        }

        if ($exchange->successful()) {
            $this->logger->info("myDATA $action answered in $milliseconds ms", $context);

            return;
        }

        $context['exception'] = $exchange->exception;
        $level = $exchange->exception instanceof RateLimitExceededException ? 'warning' : 'error';

        $this->logger->log($level, "myDATA $action failed after $milliseconds ms: {$exchange->exception->getMessage()}", $context);
    }

    public function retrying(Retry $retry): void
    {
        $this->logger->warning("myDATA {$retry->action()}: retry $retry->attempt in $retry->delay ms after: {$retry->reason->getMessage()}", [
            'action' => $retry->action(),
            'attempt' => $retry->attempt,
            'delay_ms' => $retry->delay,
            'exception' => $retry->reason,
        ]);
    }
}
