<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Writes the requests of a connection to a PSR-3 logger (Connection::withLogger()):
 * an answered request at info, a retry or a throttled request at warning, any other
 * failure at error. The credentials are never logged. The request and response XML, the
 * query parameters that identify someone (VAT numbers, QR URLs) and the failures' messages
 * and exceptions as they are, are logged only when asked for; otherwise the messages are
 * redacted and the exception is named by class and code.
 */
final readonly class ExchangeLogger
{
    /**
     * The query parameters that identify no one, logged without $withBodies. Every other
     * parameter (entityVatNumber, receiverVatNumber, vatNumber, issuervat, qrUrl, and the opaque
     * continuation keys nextPartitionKey/nextRowKey, which may encode the entity) is left out.
     */
    private const array SAFE_QUERY = ['mark' => true, 'maxMark' => true, 'dateFrom' => true, 'dateTo' => true, 'invType' => true, 'GroupedPerDay' => true, 'postPerInvoice' => true, 'stmtId' => true, 'maxstmtId' => true, 'recallStatus' => true, 'recallDate' => true, 'groupId' => true];

    public function __construct(private LoggerInterface $logger, private bool $withBodies = false) {}

    public function sent(Exchange $exchange): void
    {
        $milliseconds = (int) round($exchange->duration * 1000);
        $action = $exchange->action();

        $context = [
            'action' => $action,
            'method' => $exchange->method,
            'url' => $exchange->url,
            'query' => $this->withBodies ? $exchange->query : array_intersect_key($exchange->query, self::SAFE_QUERY),
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

        $level = $exchange->rateLimited() ? 'warning' : 'error';

        $this->logger->log($level, "myDATA $action failed after $milliseconds ms: {$this->message($exchange->exception)}", $context + $this->exception($exchange->exception));
    }

    public function retrying(Retry $retry): void
    {
        $this->logger->warning("myDATA {$retry->action()}: retry $retry->attempt in $retry->delay ms after: {$this->message($retry->reason)}", [
            'action' => $retry->action(),
            'attempt' => $retry->attempt,
            'delay_ms' => $retry->delay,
        ] + $this->exception($retry->reason));
    }

    /**
     * The exception's message. Without $withBodies it is redacted: a failure's message can
     * carry the request URL with its query (Guzzle's "GET https://…?receiverVatNumber=…"),
     * myDATA's answer, or a VAT number in one of myDATA's errors.
     */
    private function message(Throwable $exception): string
    {
        $message = $exception->getMessage();

        if ($this->withBodies) {
            return $message;
        }

        $message = preg_replace('~(https?://[^\s?`\'"]+)\?[^\s`\'"]*~', '$1?…', $message) ?? $message;

        // Greek (9 digits) and other national VAT numbers (10-11 digits, or a country prefix and 8-12 characters)
        return preg_replace('/(?<!\w)(?<!\d[.,])(?:\d{9,11}|[A-Z]{2}(?=[0-9A-Z]*\d)[0-9A-Z]{8,12})(?!\w)(?![.,]\d)/', '*********', $message) ?? $message;
    }

    /**
     * The exception for the context: the exception itself with $withBodies, as PSR-3 has it;
     * without, its class and code only, since its message and its previous exceptions are not redacted.
     *
     * @return array<string, mixed>
     */
    private function exception(Throwable $exception): array
    {
        return $this->withBodies
            ? ['exception' => $exception]
            : ['exception_class' => $exception::class, 'exception_code' => $exception->getCode()];
    }
}
