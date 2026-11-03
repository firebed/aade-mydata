<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Everything a request needs to reach myDATA: the credentials, the environment, the
 * channel (ERP or provider), the transport options, the retry policy and the gateway.
 *
 * A connection is immutable: every with*() method returns a changed copy, so one
 * connection per tenant can be built once and shared. The static setters of
 * MyDataRequest configure the process-wide default connection; pass a connection
 * to a request with MyDataRequest::using(), or use the MyData client.
 *
 * <pre>
 * $connection = Connection::make($userId, $subscriptionKey, Environment::Production)
 *     ->withTimeout(30)
 *     ->withRetries(3);
 *
 * $mydata = new MyData($connection);
 * $mydata->sendInvoices($invoice);
 * </pre>
 *
 * @phpstan-type Handler callable(RequestInterface, array<array-key, mixed>): PromiseInterface<ResponseInterface, mixed>
 */
final class Connection
{
    private ?Client $client = null;

    /**
     * @param  array<string, mixed>  $requestOptions  Guzzle request options (verify, timeout, connect_timeout, proxy, …)
     * @param  int  $retries  How many times a request is retried on 429, 502, 503, 504 or a connection failure (0 = never)
     * @param  int  $retryDelay  Delay before the first retry in milliseconds; it doubles on every further retry
     * @param  HandlerStack<Handler>|null  $handler  A Guzzle handler stack, e.g. built on a MockHandler in tests; null uses Guzzle's default
     * @param  int  $rateLimitWait  Longest total wait, in seconds, to sit out a 429 before giving up (0 = never wait)
     */
    public function __construct(
        private string $userId = '',
        private string $subscriptionKey = '',
        private Environment $environment = Environment::Development,
        private bool $provider = false,
        private array $requestOptions = [],
        private int $retries = 0,
        private int $retryDelay = 500,
        private ?Gateway $gateway = null,
        private ?HandlerStack $handler = null,
        private ?Throttle $throttle = null,
        private int $rateLimitWait = 0,
    ) {}

    public static function make(string $userId, string $subscriptionKey, Environment|string $environment = Environment::Development, bool $provider = false): self
    {
        return new self($userId, $subscriptionKey, Environment::parse($environment), $provider);
    }

    public function __clone()
    {
        // The Guzzle client depends on the options, so a changed copy builds its own
        $this->client = null;
    }

    public function withCredentials(string $userId, string $subscriptionKey): self
    {
        $copy = clone $this;
        $copy->userId = $userId;
        $copy->subscriptionKey = $subscriptionKey;

        return $copy;
    }

    public function withEnvironment(Environment|string $environment): self
    {
        $copy = clone $this;
        $copy->environment = Environment::parse($environment);

        return $copy;
    }

    /**
     * Route the requests through the e-invoicing provider channel (myDataProvider) instead of the ERP one.
     */
    public function asProvider(bool $provider = true): self
    {
        $copy = clone $this;
        $copy->provider = $provider;

        return $copy;
    }

    /**
     * Replaces the Guzzle request options.
     *
     * @param  array<string, mixed>  $requestOptions
     *
     * @see https://docs.guzzlephp.org/en/stable/request-options.html
     */
    public function withRequestOptions(array $requestOptions): self
    {
        $copy = clone $this;
        $copy->requestOptions = $requestOptions;

        return $copy;
    }

    public function withRequestOption(string $option, mixed $value): self
    {
        return $this->withRequestOptions([$option => $value] + $this->requestOptions);
    }

    /**
     * SSL certificate verification: true (system CA bundle), false (insecure, dev only) or the path of a CA bundle.
     */
    public function withVerify(bool|string $verify): self
    {
        return $this->withRequestOption('verify', $verify);
    }

    /**
     * Total time allowed for a request, in seconds (0 waits indefinitely).
     */
    public function withTimeout(int|float $seconds): self
    {
        return $this->withRequestOption('timeout', $seconds);
    }

    /**
     * Time allowed to connect to myDATA, in seconds (0 waits indefinitely).
     */
    public function withConnectionTimeout(int|float $seconds): self
    {
        return $this->withRequestOption('connect_timeout', $seconds);
    }

    /**
     * Retry a request that fails with 429, 502, 503, 504 or a connection failure. A
     * timeout is never retried: myDATA may have processed the request already.
     *
     * @param  int  $retries  Number of retries (0 disables retrying)
     * @param  int  $delay  Delay before the first retry in milliseconds; doubles on every further retry, honouring a Retry-After header
     */
    public function withRetries(int $retries, int $delay = 500): self
    {
        $copy = clone $this;
        $copy->retries = max(0, $retries);
        $copy->retryDelay = max(0, $delay);

        return $copy;
    }

    /**
     * Keep at least this many milliseconds between two requests of this connection and
     * of every connection derived from it. myDATA throttles a subscription key by the
     * number of calls in a window of about five minutes (see the rate limit section of
     * the docs), so spacing calls that page through documents keeps a long sync from
     * locking the key out. Zero removes the throttle.
     */
    public function withThrottle(int $milliseconds): self
    {
        $copy = clone $this;
        $copy->throttle = $milliseconds > 0 ? new Throttle($milliseconds) : null;

        return $copy;
    }

    /**
     * When myDATA throttles the key (HTTP 429, "Try again in N seconds"), wait out the
     * N seconds it asks for and send the request again, for up to this many seconds in
     * total per request. Meant for syncs that page through documents in a worker, where
     * sitting out the five-minute window beats failing. Zero (the default) never waits
     * longer than the retry backoff: the request fails with RateLimitExceededException,
     * whose getRetryAfter() says when to try again.
     */
    public function withRateLimitWait(int $maxSeconds = 600): self
    {
        $copy = clone $this;
        $copy->rateLimitWait = max(0, $maxSeconds);

        return $copy;
    }

    /**
     * The component that carries the requests. Null restores the default GuzzleGateway.
     */
    public function withGateway(?Gateway $gateway): self
    {
        $copy = clone $this;
        $copy->gateway = $gateway;

        return $copy;
    }

    /**
     * A Guzzle handler stack, e.g. built on a MockHandler in tests. Null uses Guzzle's default.
     *
     * @param  HandlerStack<Handler>|null  $handler
     */
    public function withHandler(?HandlerStack $handler): self
    {
        $copy = clone $this;
        $copy->handler = $handler;

        return $copy;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function subscriptionKey(): string
    {
        return $this->subscriptionKey;
    }

    public function environment(): Environment
    {
        return $this->environment;
    }

    public function isProduction(): bool
    {
        return $this->environment->isProduction();
    }

    public function isDevelopment(): bool
    {
        return ! $this->environment->isProduction();
    }

    public function isProvider(): bool
    {
        return $this->provider;
    }

    public function hasCredentials(): bool
    {
        return $this->userId !== '' && $this->subscriptionKey !== '';
    }

    /**
     * @return array<string, mixed>
     */
    public function requestOptions(): array
    {
        return $this->requestOptions;
    }

    public function retries(): int
    {
        return $this->retries;
    }

    /**
     * Delay before the first retry, in milliseconds.
     */
    public function retryDelay(): int
    {
        return $this->retryDelay;
    }

    public function throttle(): ?Throttle
    {
        return $this->throttle;
    }

    /**
     * The longest total wait, in seconds, to sit out a 429 per request (0 = do not wait).
     */
    public function rateLimitWait(): int
    {
        return $this->rateLimitWait;
    }

    public function gateway(): Gateway
    {
        return $this->gateway ??= new GuzzleGateway;
    }

    /**
     * @return HandlerStack<Handler>|null
     */
    public function handler(): ?HandlerStack
    {
        return $this->handler;
    }

    public function baseUrl(): string
    {
        return $this->environment->baseUrl($this->provider);
    }

    public function url(string $action): string
    {
        return $this->baseUrl().$action;
    }

    /**
     * The Guzzle client of this connection, built once and reused for every request.
     */
    public function httpClient(): Client
    {
        if ($this->client === null) {
            $options = $this->requestOptions;

            // Custom headers are added to the authentication headers, never in their place
            $options['headers'] = array_replace([
                'aade-user-id' => $this->userId,
                'ocp-apim-subscription-key' => $this->subscriptionKey,
                'Content-Type' => 'text/xml',
            ], is_array($options['headers'] ?? null) ? $options['headers'] : []);

            if ($this->handler !== null) {
                $options['handler'] = $this->handler;
            }

            $this->client = new Client($options);
        }

        return $this->client;
    }
}
