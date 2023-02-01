<?php

namespace Firebed\AadeMyData\Http;

use Error;
use Firebed\AadeMyData\Loader;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Parser\RequestedDocParser;
use Firebed\AadeMyData\Parser\ResponseDocParser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class MyDataRequest
{
    private static ?string     $user_id          = null;
    private static ?string     $subscription_key = null;
    private static ?string     $env              = null;
    private static bool|string $verify_client    = true;

    private static mixed $mockResponse;

    public static function mockResponse($mockResponse): void
    {
        if (is_callable($mockResponse)) {
            self::$mockResponse = $mockResponse;
            return;
        }

        self::$mockResponse = fn() => $mockResponse;
    }

    public static function isMocking(): bool
    {
        return isset(self::$mockResponse);
    }

    public static function init(string $user_id, string $subscription_key, string $env): void
    {
        self::setCredentials($user_id, $subscription_key);
        self::setEnvironment($env);
    }

    public static function setCredentials(string $user_id, string $subscription_key): void
    {
        self::$user_id = $user_id;
        self::$subscription_key = $subscription_key;
    }

    /**
     * <ul>Describes the SSL certificate verification behavior of a request.
     *
     * <li>Set to true to enable SSL certificate verification and use the default CA bundle provided by operating system.</li>
     * <li>Set to false to disable certificate verification (this is insecure!).</li>
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
     * @param bool|string $verify
     * @return void
     */
    public static function verifyClient(bool|string $verify = true): void
    {
        self::$verify_client = $verify;
    }

    public static function setEnvironment($env): void
    {
        self::$env = $env;
    }

    public static function isDevelopment(): bool
    {
        return self::$env === 'dev';
    }

    public static function isProduction(): bool
    {
        return self::$env === 'prod';
    }

    /**
     * @throws GuzzleException
     */
    protected function get(array $query): mixed
    {
        self::validateCredentials();

        $url = Loader::getUrl(get_class($this), self::$env);

        if (self::isMocking()) {
            return self::mock();
        }

        $response = $this->client()->get($url, ['query' => $query]);

        return RequestedDocParser::parseXML(simplexml_load_string($response->getBody()->getContents()));
    }

    /**
     * @throws GuzzleException
     */
    protected function post(array $query = null, string $body = null): ResponseDoc
    {
        self::validateCredentials();

        $url = Loader::getUrl(get_class($this), self::$env);

        if (self::isMocking()) {
            return self::mock();
        }

        $params = [];
        if (!empty($query)) {
            $params['query'] = $query;
        }

        if (!empty($body)) {
            $params['body'] = $body;
        }

        $response = $this->client()->post($url, $params);
        $xml = simplexml_load_string($response->getBody()->getContents());

        return ResponseDocParser::parseXML($xml);
    }

    private function mock()
    {
        $callback = self::$mockResponse;
        return $callback();
    }

    private function client(): Client
    {
        return new Client([
            'headers' => [
                'aade-user-id'              => self::$user_id,
                'Ocp-Apim-Subscription-Key' => self::$subscription_key,
                'Content-Type'              => "text/xml"
            ],
            'verify'  => self::$verify_client
        ]);
    }

    private static function validateCredentials(): void
    {
        if (empty(self::$user_id) || empty(self::$subscription_key)) {
            throw new Error("Missing credentials. Please use MyDataRequest::setCredentials method to set your myDATA Rest API credentials.");
        }
    }
}
