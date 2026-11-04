<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use BackedEnum;
use Firebed\AadeMyData\Exceptions\InvalidResponseException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Models\Paginated;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Base of every myDATA request. A request is carried over a Connection: the one given
 * with using() (or by the MyData client), else the process-wide default connection
 * configured through the static setters below (init(), setCredentials(), …).
 */
abstract class MyDataRequest
{
    /**
     * The myDATA action of the request; the short class name when not declared.
     */
    protected string $action;

    private static ?Connection $default = null;

    private static ?Gateway $intercept = null;

    private ?Connection $connection = null;

    private ?Gateway $gateway_override = null;

    /**
     * The default connection, used by every request that is not given one with using().
     */
    public static function defaultConnection(): Connection
    {
        return self::$default ??= new Connection;
    }

    /**
     * Replaces the default connection. Null resets it to an empty one.
     */
    public static function setDefaultConnection(?Connection $connection): void
    {
        self::$default = $connection;
    }

    /**
     * Use a Guzzle mock handler for the default connection (tests). Null restores the real transport.
     */
    public static function setHandler(?MockHandler $handler): void
    {
        self::$default = self::defaultConnection()->withHandler($handler === null ? null : HandlerStack::create($handler));
    }

    /**
     * Initialize the myDATA API with the user_id, subscription_key, and environment.
     *
     * @param  string  $user_id  The user id provided by AADE
     * @param  string  $subscription_key  The subscription key provided by AADE
     * @param  Environment|string  $env  'dev' or 'prod'
     * @param  bool  $is_provider  Set to true if the request is for the providers
     */
    public static function init(string $user_id, string $subscription_key, Environment|string $env, bool $is_provider = false): void
    {
        self::setCredentials($user_id, $subscription_key);
        self::setEnvironment($env, $is_provider);
    }

    /**
     * Set the user_id and subscription_key for the myDATA API.
     *
     * @param  string  $user_id  The user id provided by AADE
     * @param  string  $subscription_key  The subscription key provided by AADE
     */
    public static function setCredentials(string $user_id, string $subscription_key): void
    {
        self::$default = self::defaultConnection()->withCredentials($user_id, $subscription_key);
    }

    /**
     * Set the environment to either 'dev' or 'prod'.
     *
     * @param  Environment|string  $env  'dev' or 'prod'
     * @param  bool  $is_provider  Set to true if the request is for the providers
     */
    public static function setEnvironment(Environment|string $env, bool $is_provider = false): void
    {
        self::$default = self::defaultConnection()->withEnvironment($env)->asProvider($is_provider);
    }

    /**
     * <ul>Describes the SSL certificate verification behavior of a request.
     *
     * <li>Set to <code>true</code> to enable SSL certificate verification and use the default CA bundle provided by operating system.</li>
     * <li>Set to <code>false</code> to disable certificate verification (this is insecure!).</li>
     * <li>Set to a string to provide the path to a CA bundle to enable verification using a custom certificate.</li>
     *
     * </ul>
     *
     * <pre>
     * // Use the system's CA bundle (this is the default setting)
     * MyDataRequest::verifyClient(true);
     *
     * // Use a custom SSL certificate on disk.
     * MyDataRequest::verifyClient('/path/to/cert.pem');
     *
     * // Disable validation entirely (don't do this!).
     * MyDataRequest::verifyClient(false);
     * </pre>
     *
     * <br>
     * <p>If you do not need a specific certificate bundle, then Mozilla provides a commonly used CA bundle which can be downloaded <a href="https://curl.haxx.se/ca/cacert.pem">here</a>
     * (provided by the maintainer of cURL). Once you have a CA bundle available on disk, you can set the "openssl.cafile" PHP ini
     * setting to point to the path to the file, allowing you to omit the "verify" request option.
     * Much more detail on SSL certificates can be found on the <a href="http://curl.haxx.se/docs/sslcerts.html">cURL website</a>.</p>
     */
    public static function verifyClient(bool|string $verify = true): void
    {
        self::$default = self::defaultConnection()->withVerify($verify);
    }

    /**
     * The number of seconds to wait while trying to connect to myDATA server.
     * Use 0 to wait indefinitely (the default behavior).
     */
    public static function setConnectionTimeout(int $seconds): void
    {
        self::$default = self::defaultConnection()->withConnectionTimeout($seconds);
    }

    /**
     * Total time for the request.
     */
    public static function setTimeout(int $seconds): void
    {
        self::$default = self::defaultConnection()->withTimeout($seconds);
    }

    /**
     * You can customize requests created and transferred by a client using request options.
     * Request options control various aspects of a request including, headers, query string
     * parameters, timeout settings, the body of a request, and much more.
     *
     * @param  array<string, mixed>  $requestOptions
     *
     * @see https://docs.guzzlephp.org/en/stable/request-options.html
     */
    public static function setRequestOptions(array $requestOptions): void
    {
        self::$default = self::defaultConnection()->withRequestOptions($requestOptions);
    }

    /**
     * Retry requests that fail with 429, 502, 503, 504 or a connection failure.
     *
     * @param  int  $retries  Number of retries (0 disables retrying)
     * @param  int  $delay  Delay before the first retry in milliseconds, doubled on every further retry
     */
    public static function setRetries(int $retries, int $delay = 500): void
    {
        self::$default = self::defaultConnection()->withRetries($retries, $delay);
    }

    /**
     * Keep at least this many milliseconds between two requests of the default connection,
     * so that a loop over many pages or marks stays under myDATA's rate limit.
     */
    public static function setThrottle(int $milliseconds): void
    {
        self::$default = self::defaultConnection()->withThrottle($milliseconds);
    }

    /**
     * Sit out a 429 of up to this many seconds and send the request again, instead of failing.
     */
    public static function setRateLimitWait(int $maxSeconds = 600): void
    {
        self::$default = self::defaultConnection()->withRateLimitWait($maxSeconds);
    }

    /**
     * Log every request, retry and failure of the default connection to a PSR-3 logger,
     * in place of the logger set before; null stops logging.
     */
    public static function setLogger(?LoggerInterface $logger, bool $withBodies = false): void
    {
        self::$default = self::defaultConnection()->withLogger($logger, $withBodies);
    }

    /**
     * Call the listener after every request of the default connection, see Connection::afterSending().
     *
     * @param  callable(Exchange): void  $listener
     */
    public static function afterSending(callable $listener, ?string $key = null): void
    {
        self::$default = self::defaultConnection()->afterSending($listener, $key);
    }

    /**
     * Call the listener before every retry of the default connection, see Connection::beforeRetry().
     *
     * @param  callable(Retry): void  $listener
     */
    public static function beforeRetry(callable $listener, ?string $key = null): void
    {
        self::$default = self::defaultConnection()->beforeRetry($listener, $key);
    }

    /**
     * Remove a listener of the default connection by key or by itself, see Connection::withoutListener().
     */
    public static function removeListener(string|callable $listener): void
    {
        self::$default = self::defaultConnection()->withoutListener($listener);
    }

    /**
     * Remove every listener of the default connection, see Connection::withoutListeners().
     */
    public static function removeListeners(): void
    {
        self::$default = self::defaultConnection()->withoutListeners();
    }

    /**
     * Carry every request through this gateway, whatever its connection or usingGateway()
     * says, until called with null. MyData::fake() uses it, so that no request of the
     * code under test reaches myDATA.
     */
    public static function intercept(?Gateway $gateway): void
    {
        self::$intercept = $gateway;
    }

    public static function isProduction(): bool
    {
        return self::defaultConnection()->isProduction();
    }

    public static function isDevelopment(): bool
    {
        return self::defaultConnection()->isDevelopment();
    }

    public static function isProvider(): bool
    {
        return self::defaultConnection()->isProvider();
    }

    public static function hasCredentials(): bool
    {
        return self::defaultConnection()->hasCredentials();
    }

    /**
     * Replace the component that carries requests to the remote system.
     * Pass null to restore the default GuzzleGateway (AADE myDATA REST API).
     */
    public static function setGateway(?Gateway $gateway): void
    {
        self::$default = self::defaultConnection()->withGateway($gateway);
    }

    public static function gateway(): Gateway
    {
        return self::defaultConnection()->gateway();
    }

    /**
     * The Guzzle client of the default connection.
     */
    public static function httpClient(): Client
    {
        return self::defaultConnection()->httpClient();
    }

    /**
     * Carry this request over the given connection instead of the default one.
     */
    public function using(?Connection $connection): static
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Carry only this request through the given gateway, leaving the connection's
     * one - and every other request - untouched. Pass null to fall back to it.
     *
     * Prefer this over setGateway() when the gateway is chosen per tenant or per flow,
     * because setGateway() lives for the whole process.
     */
    public function usingGateway(?Gateway $gateway): static
    {
        $this->gateway_override = $gateway;

        return $this;
    }

    /**
     * The connection this request is carried over.
     */
    public function connection(): Connection
    {
        return $this->connection ?? self::defaultConnection();
    }

    private function resolveGateway(): Gateway
    {
        return self::$intercept ?? $this->gateway_override ?? $this->connection()->gateway();
    }

    /**
     * @param  array<string, mixed>  $query
     *
     * @throws MyDataException
     */
    protected function get(array $query): string
    {
        return $this->transmit('GET', $query);
    }

    /**
     * @param  array<string, mixed>|null  $query
     *
     * @throws MyDataException
     */
    protected function post(?array $query = null, ?string $body = null): string
    {
        return $this->transmit('POST', $query, $body);
    }

    /**
     * Carries the request through its gateway and reports the outcome to the
     * connection's listeners (Connection::afterSending()).
     *
     * @param  'GET'|'POST'  $method
     * @param  array<string, mixed>|null  $query
     *
     * @throws MyDataException
     */
    private function transmit(string $method, ?array $query, ?string $body = null): string
    {
        $gateway = $this->resolveGateway();
        $start = hrtime(true);
        $response = $error = null;

        try {
            return $response = self::ensureXml($method === 'GET' ? $gateway->get($this, $query ?? []) : $gateway->post($this, $query, $body));
        } catch (Throwable $error) {
            throw $error;
        } finally {
            $this->connection()->sent(new Exchange($this, $method, $this->getUrl(), $query ?? [], $body, $response, $error, (hrtime(true) - $start) / 1e9));
        }
    }

    /**
     * We always expect a response XML from myDATA, no matter which gateway carried the request.
     *
     * @throws InvalidResponseException
     */
    private static function ensureXml(string $responseXml): string
    {
        if (trim($responseXml) === '') {
            throw new InvalidResponseException('Empty response received from AADE MyData API');
        }

        return $responseXml;
    }

    /**
     * Query parameters that have a value: null and '' are dropped, enums become their value.
     *
     * @param  array<string, mixed>  $array
     * @return array<string, mixed>
     */
    protected function filterArray(array $array): array
    {
        $query = [];
        foreach ($array as $key => $value) {
            if ($value instanceof BackedEnum) {
                $value = $value->value;
            }

            if ($value !== null && $value !== '') {
                $query[$key] = $value;
            }
        }

        return $query;
    }

    public function getUrl(): string
    {
        return $this->connection()->url($this->action());
    }

    /**
     * Fetches page after page, lazily, following the continuation token until a page
     * has none. A token myDATA repeats ends the iteration instead of looping forever.
     *
     * @template TPage of Paginated
     *
     * @param  callable(?string, ?string): TPage  $fetch  Fetches the page for a partition key and row key
     * @return Generator<int, TPage>
     */
    protected function paginate(callable $fetch, ?string $nextPartitionKey = null, ?string $nextRowKey = null): Generator
    {
        do {
            $page = $fetch($nextPartitionKey, $nextRowKey);

            yield $page;

            $previous = [$nextPartitionKey, $nextRowKey];
            $token = $page->getContinuationToken();
            $nextPartitionKey = $token?->getNextPartitionKey();
            $nextRowKey = $token?->getNextRowKey();
        } while ($nextPartitionKey !== null && $nextRowKey !== null && [$nextPartitionKey, $nextRowKey] !== $previous);
    }

    /**
     * The myDATA action of the request, e.g. 'SendInvoices'.
     */
    public function action(): string
    {
        return $this->action ?? $this->shortName();
    }

    private function shortName(): string
    {
        return substr(static::class, (int) strrpos(static::class, '\\') + 1);
    }

    /**
     * @throws UnsupportedChannelException
     */
    protected function ensureProvider(): void
    {
        if (! $this->connection()->isProvider()) {
            throw new UnsupportedChannelException($this->shortName().' can only be used with the Provider route.');
        }
    }

    /**
     * @throws UnsupportedChannelException
     */
    protected function ensureERP(): void
    {
        if ($this->connection()->isProvider()) {
            throw new UnsupportedChannelException($this->shortName().' can only be used with the ERP route.');
        }
    }
}
