<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\ErrorResponseException;
use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataConnectionException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\MyDataTimeoutException;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\NetworkTimeoutException;
use GuzzleHttp\Exception\ResponseTimeoutException;

/**
 * Default gateway: sends requests to the AADE myDATA REST API using Guzzle, spacing
 * them as the connection's throttle asks (Connection::withThrottle()) and retrying
 * them as its retry policy allows (Connection::withRetries(), withRateLimitWait()).
 *
 * Works with Guzzle 7 and 8. They differ in how a failed transfer is reported: a
 * response, when there is one, is read from BadResponseException (both majors), and
 * timeouts are told apart from connection failures by Guzzle 8's exception classes
 * or by cURL error 28 in Guzzle 7's handler context (see isTimeout()).
 */
class GuzzleGateway implements Gateway
{
    /**
     * HTTP statuses worth a retry: throttling and gateway errors, where myDATA did not process the request.
     */
    protected const array RETRYABLE_STATUSES = [429, 502, 503, 504];

    /**
     * The longest backoff between two attempts, in milliseconds. A 429 whose Retry-After
     * is longer than this is not retried (unless the connection waits out rate limits):
     * myDATA refuses the key for minutes, and hammering it meanwhile wastes the caller's time.
     */
    protected const int MAX_RETRY_DELAY = 10_000;

    /**
     * @param  array<string, mixed>  $query
     *
     * @throws MyDataAuthenticationException|MyDataException
     */
    public function get(MyDataRequest $request, array $query): string
    {
        return $this->send('GET', $request, ['query' => $query]);
    }

    /**
     * @param  array<string, mixed>|null  $query
     *
     * @throws MyDataAuthenticationException|MyDataException
     */
    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string
    {
        return $this->send('POST', $request, array_filter(['query' => $query, 'body' => $body]));
    }

    /**
     * @param  array<string, mixed>  $options
     *
     * @throws MyDataAuthenticationException|MyDataException
     */
    protected function send(string $method, MyDataRequest $request, array $options): string
    {
        $connection = $request->connection();

        if (! $connection->hasCredentials()) {
            throw new MyDataAuthenticationException(401);
        }

        $client = $connection->httpClient();
        $url = $request->getUrl();
        $waited = 0;

        for ($attempt = 0; ; $attempt++) {
            $pause = $connection->throttle()?->pause() ?? 0;

            if ($pause > 0) {
                $this->sleep($pause);
            }

            try {
                return $client->request($method, $url, $options)->getBody()->getContents();
            } catch (GuzzleException $e) {
                $body = $this->body($e);
                $retryAfter = $e->getCode() === 429 ? RateLimitExceededException::parseRetryAfter($this->header($e, 'Retry-After'), $body) : null;

                $delay = $this->delayBefore($attempt, $waited, $retryAfter, $e, $connection);

                if ($delay === null) {
                    throw $this->exception($e, $body, $retryAfter);
                }

                $connection->retrying(new Retry($request, $attempt + 1, $delay, $this->exception($e, $body, $retryAfter)));

                $waited += $delay;
                $this->sleep($delay);
            }
        }
    }

    /**
     * How long to wait before trying again, in milliseconds, or null to give up:
     *
     * - a 429 is waited out as long as the connection's rate-limit wait allows (not
     *   counted as a retry, but bounded, so a key that stays throttled still fails),
     * - otherwise a retryable failure gets the Retry-After myDATA asks for when it fits
     *   in MAX_RETRY_DELAY, else the connection's backoff doubled on every attempt,
     * - while retries remain.
     */
    protected function delayBefore(int $attempt, int $waited, ?int $retryAfter, GuzzleException $exception, Connection $connection): ?int
    {
        if ($retryAfter !== null && $connection->rateLimitWait() > 0) {
            $wait = ($retryAfter + 1) * 1000; // one second more: myDATA counts whole seconds

            if ($waited + $wait <= $connection->rateLimitWait() * 1000) {
                return $wait;
            }
        }

        if ($attempt >= $connection->retries() || ! $this->isRetryable($exception)) {
            return null;
        }

        if ($retryAfter !== null) {
            return $retryAfter * 1000 <= static::MAX_RETRY_DELAY ? $retryAfter * 1000 : null;
        }

        return (int) min($connection->retryDelay() * 2 ** $attempt, static::MAX_RETRY_DELAY);
    }

    /**
     * Throttling, gateway errors and connection failures are safe to retry. A timeout
     * is not: the request may have reached myDATA and been processed.
     */
    protected function isRetryable(GuzzleException $exception): bool
    {
        if ($this->isTimeout($exception)) {
            return false;
        }

        // No response (code 0): a connection failure, in both Guzzle majors
        return $exception->getCode() === 0 || in_array($exception->getCode(), static::RETRYABLE_STATUSES, true);
    }

    /**
     * The request went out, but the answer took too long: myDATA may have processed it.
     *
     * Guzzle 8 tells the timeouts apart: before the response headers
     * (NetworkTimeoutException) or after them (ResponseTimeoutException). A connect
     * timeout (ConnectTimeoutException) is neither: the request never left, so it is
     * retried like any other connection failure. Guzzle 7 reports every timeout as
     * cURL error 28 in the handler context of a ConnectException or RequestException,
     * whatever the stage, so there all of them count as timeouts.
     */
    protected function isTimeout(GuzzleException $exception): bool
    {
        if ($exception instanceof NetworkTimeoutException || $exception instanceof ResponseTimeoutException) {
            return true;
        }

        return method_exists($exception, 'getHandlerContext') && ($exception->getHandlerContext()['errno'] ?? null) === 28;
    }

    protected function sleep(int $milliseconds): void
    {
        if ($milliseconds > 0) {
            usleep($milliseconds * 1000);
        }
    }

    /**
     * Maps a failed transfer to the matching MyDataException: timeout, unreachable host,
     * authentication, throttling, an error document, or any other HTTP error.
     */
    protected function exception(GuzzleException $exception, string $body, ?int $retryAfter): MyDataException
    {
        if ($this->isTimeout($exception)) {
            return new MyDataTimeoutException(previous: $exception);
        }

        // No response at all (a wrong endpoint, DNS failure, refused connection or
        // connect timeout): myDATA is unreachable
        if ($exception->getCode() === 0) {
            return new MyDataConnectionException($exception->getCode(), $exception);
        }

        // Authentication failed, or the user is not authorized for the requested
        // resource. Keep the reason myDATA gives so the two can be told apart.
        if ($exception->getCode() === 401) {
            return new MyDataAuthenticationException(401, $exception, $this->reason($body));
        }

        // myDATA throttled the subscription key; it says how long to wait
        if ($exception->getCode() === 429) {
            return new RateLimitExceededException($this->reason($body) ?? $exception->getMessage(), 429, $exception, $retryAfter);
        }

        // myDATA can also answer a bad request with an error document (e.g. HTTP 400).
        // The HTTP status stays the exception code, as it was before the errors were read.
        return ErrorResponseException::fromXml($body, $exception->getCode(), $exception)
            ?? new TransmissionFailedException($body ?: $exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * The body of a failed response, read from the start even if it has been read before.
     */
    private function body(GuzzleException $exception): string
    {
        return $exception instanceof BadResponseException ? trim((string) $exception->getResponse()->getBody()) : '';
    }

    private function header(GuzzleException $exception, string $name): ?string
    {
        $value = $exception instanceof BadResponseException ? $exception->getResponse()->getHeaderLine($name) : null;

        return $value === '' ? null : $value;
    }

    /**
     * The error message of a failed response: plain text from myDATA, the "message"
     * of the JSON error returned by the API gateway, or any other JSON as it is.
     * Markup (e.g. an HTML error page from a proxy) is not a usable reason.
     */
    private function reason(string $body): ?string
    {
        $json = json_decode($body, true);
        if (is_array($json) && is_string($json['message'] ?? null)) {
            $body = trim($json['message']);
        } elseif (is_string($json)) {
            $body = trim($json);
        }

        if ($body === '' || str_starts_with($body, '<')) {
            return null;
        }

        // Keep the exception message readable: valid UTF-8 and capped in length
        $body = mb_scrub($body, 'UTF-8');

        return mb_strlen($body) > 500 ? mb_substr($body, 0, 500).'…' : $body;
    }
}
