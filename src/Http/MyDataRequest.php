<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\InvalidResponseException;
use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataConnectionException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\MyDataTimeoutException;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use ReflectionClass;

abstract class MyDataRequest
{
    private const DEV_ERP_URL  = 'https://mydataapidev.aade.gr/';
    private const PROD_ERP_URL = 'https://mydatapi.aade.gr/myDATA/';

    private const DEV_PROVIDER_URL  = 'https://mydataapidev.aade.gr/myDataProvider/';
    private const PROD_PROVIDER_URL = 'https://mydatapi.aade.gr/myDataProvider/';

    private static ?string $user_id          = null;
    private static ?string $subscription_key = null;
    private static ?string $env              = null;
    private static ?bool   $is_provider      = false;
    private static array   $request_options;

    private static ?HandlerStack $handler;

    public static function setHandler(?MockHandler $handler): void
    {
        self::$handler = HandlerStack::create($handler);
    }

    /**
     * Initialize the myDATA API with the user_id, subscription_key and environment.
     *
     * @param  string  $user_id The user id provided by AADE
     * @param  string  $subscription_key The subscription key provided by AADE
     * @param  string  $env 'dev' or 'prod'
     * @param  bool  $is_provider Set to true if the request is for the providers
     * @return void
     */
    public static function init(string $user_id, string $subscription_key, string $env, bool $is_provider = false): void
    {
        self::setCredentials($user_id, $subscription_key);
        self::setEnvironment($env, $is_provider);
    }

    /**
     * Set the user_id and subscription_key for the myDATA API.
     *
     * @param  string  $user_id The user id provided by AADE
     * @param  string  $subscription_key The subscription key provided by AADE
     * @return void
     */
    public static function setCredentials(string $user_id, string $subscription_key): void
    {
        self::$user_id = $user_id;
        self::$subscription_key = $subscription_key;
    }

    /**
     * Set the environment to either 'dev' or 'prod'.
     *
     * @param  string  $env 'dev' or 'prod'
     * @param  bool  $is_provider Set to true if the request is for the providers
     * @return void
     */
    public static function setEnvironment(string $env, bool $is_provider = false): void
    {
        self::$env = strtolower($env);
        self::$is_provider = $is_provider;
    }

    /**
     * <ul>Describes the SSL certificate verification behavior of a request.
     *
     * <li>Set to <code>true</code> to enable SSL certificate verification and use the default CA bundle provided by operating system.</li>
     * <li>Set to <code>false</code> to disable certificate verification (this is insecure!).</li>
     * <li>Set to a string to provide the path to a CA bundle to enable verification using a custom certificate.</li>
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
     *
     * @param  bool|string  $verify
     * @return void
     */
    public static function verifyClient(bool|string $verify = true): void
    {
        self::$request_options['verify'] = $verify;
    }

    /**
     * The number of seconds to wait while trying to connect to myDATA server.
     * Use 0 to wait indefinitely (the default behavior).
     *
     * @param  int  $seconds
     * @return void
     */
    public static function setConnectionTimeout(int $seconds): void
    {
        self::$request_options['connect_timeout'] = $seconds;
    }

    /**
     * Total time for the request.
     *
     * @param  int  $seconds
     * @return void
     */
    public static function setTimeout(int $seconds): void
    {
        self::$request_options['timeout'] = $seconds;
    }

    /**
     * You can customize requests created and transferred by a client using request options.
     * Request options control various aspects of a request including, headers, query string
     * parameters, timeout settings, the body of a request, and much more.
     *
     * @param  array  $requestOptions
     * @return void
     * @see https://docs.guzzlephp.org/en/stable/request-options.html
     */
    public static function setRequestOptions(array $requestOptions): void
    {
        self::$request_options = $requestOptions;
    }

    public static function isProduction(): bool
    {
        return self::$env === 'prod';
    }

    public static function isDevelopment(): bool
    {
        return self::$env === 'dev';
    }

    public static function isProvider(): bool
    {
        return self::$is_provider;
    }

    /**
     * @throws MyDataAuthenticationException
     */
    private static function validateCredentials(): void
    {
        if (empty(self::$user_id) || empty(self::$subscription_key)) {
            throw new MyDataAuthenticationException(401);
        }
    }

    /**
     * @throws MyDataAuthenticationException|MyDataException
     */
    protected function get(array $query): string
    {
        self::validateCredentials();

        try {
            $response = $this->client()->get($this->getUrl(), ['query' => $query]);
            $responseXml = $response->getBody()->getContents();

            // We always expect a response xml from myDATA
            if (empty(trim($responseXml))) {
                throw new InvalidResponseException("Empty response received from AADE MyData API");
            }

            return $responseXml;
        } catch (GuzzleException $e) {
            $this->handleTransmissionException($e);
        }
    }

    /**
     * @throws MyDataAuthenticationException|MyDataException
     */
    protected function post(array $query = null, string $body = null): string
    {
        self::validateCredentials();

        $params = [];
        if (!empty($query)) {
            $params['query'] = $query;
        }

        if (!empty($body)) {
            $params['body'] = $body;
        }

        try {
            $response = $this->client()->post($this->getUrl(), $params);
            $responseXml = $response->getBody()->getContents();

            // We always expect a response xml from myDATA
            if (empty(trim($responseXml))) {
                throw new InvalidResponseException("Empty response received from AADE MyData API");
            }

            return $responseXml;
        } catch (GuzzleException $e) {
            $this->handleTransmissionException($e);
        }
    }

    /**
     * Authorization errors, bad request, communication errors,
     * myDATA server errors, rate limits, connection timeout, etc.
     *
     * @throws MyDataAuthenticationException|MyDataException
     */
    protected function handleTransmissionException(GuzzleException $exception)
    {
        // Specific case for timeout exception (HTTP 28 for cURL)
        // Connection with myDATA was established but the response took too long
        if ($exception instanceof RequestException) {
            $errorNo = $exception->getHandlerContext()['errno'] ?? null;
            if ($errorNo === 28) {
                throw new MyDataTimeoutException(previous: $exception);
            }
        }

        // In case the endpoint url is wrong or connection timed out, myDATA is unreachable
        if ($exception->getCode() === 0) {
            throw new MyDataConnectionException($exception->getCode(), $exception);
        }

        // Authentication with myDATA failed
        if ($exception->getCode() === 401) {
            throw new MyDataAuthenticationException($exception->getCode(), $exception);
        }

        // Rate limit exception
        if ($exception->getCode() === 429) {
            throw new RateLimitExceededException($exception->getMessage(), $exception->getCode(), $exception);
        }

        throw new TransmissionFailedException($exception->getMessage(), $exception->getCode(), $exception);
    }

    private function client(): Client
    {
        $config = [
            'headers' => [
                'aade-user-id' => self::$user_id,
                'ocp-apim-subscription-key' => self::$subscription_key,
                'Content-Type' => "text/xml",
            ],
        ];

        if (isset(self::$handler)) {
            $config['handler'] = self::$handler;
        }

        if (isset(self::$request_options)) {
            $config = array_merge($config, self::$request_options);
        }

        return new Client($config);
    }

    public function getUrl(): string
    {
        $action = $this->getAction();
        $url = self::$is_provider ? $this->getUrlForProvider() : $this->getUrlForErp();

        return $url.$action;
    }

    private function getUrlForErp(): string
    {
        return self::isProduction() ? self::PROD_ERP_URL : self::DEV_ERP_URL;
    }

    private function getUrlForProvider(): string
    {
        return self::isProduction() ? self::PROD_PROVIDER_URL : self::DEV_PROVIDER_URL;
    }

    private function getAction(): string
    {
        return $this->action ?? (new ReflectionClass($this))->getShortName();
    }

    /**
     * @throws MyDataException
     */
    protected function ensureProvider(): void
    {
        if (!$this->isProvider()) {
            $className = (new ReflectionClass($this))->getShortName();
            throw new MyDataException($className . ' can only be used with the Provider route.');
        }
    }
}
