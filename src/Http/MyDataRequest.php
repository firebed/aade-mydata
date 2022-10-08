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
    private static ?string $user_id          = null;
    private static ?string $subscription_key = null;
    private static ?string $env              = null;

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
        ]);
    }

    private static function validateCredentials(): void
    {
        if (empty(self::$user_id) || empty(self::$subscription_key)) {
            throw new Error("Missing credentials. Please use MyDataRequest::setCredentials method to set your myDATA Rest API credentials.");
        }
    }
}
