<?php

namespace Firebed\AadeMyData\Http;

use Error;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Parser\RequestedDocParser;
use Firebed\AadeMyData\Parser\ResponseDocParser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MyDataRequest
{
    private static string $user_id;
    private static string $subscription_key;
    private static string $env;
    private string        $url;

    public function __construct()
    {
        $urls = require __DIR__ . '/../../config/urls.php';
        $this->url = $urls[self::$env][get_class($this)];
    }

    public static function setCredentials($user_id, $subscription_key): void
    {
        self::$user_id = $user_id;
        self::$subscription_key = $subscription_key;
    }

    public static function setEnvironment($env): void
    {
        self::$env = $env;
    }

    public function isDevelopment(): bool
    {
        return self::$env === 'dev';
    }

    public function isProduction(): bool
    {
        return !$this->isDevelopment();
    }

    protected function get(array $query): RequestedDoc
    {
        self::validate();
        
        try {
            $response = $this->client()->get($this->url, ['query' => $query]);
            return RequestedDocParser::parseXML(simplexml_load_string($response->getBody()));
        } catch (GuzzleException $e) {
            die($e->getMessage());
        }
    }

    protected function post(array $query = null, string $body = null): ResponseDoc
    {
        self::validate();
        
        $params = [];
        if (!empty($query)) {
            $params['query'] = $query;
        }

        if (!empty($body)) {
            $params['body'] = $body;
        }

        try {
            $response = $this->client()->post($this->url, $params);
            $xml = simplexml_load_string($response->getBody());
            return ResponseDocParser::parseXML($xml);
        } catch (GuzzleException $e) {
            die($e->getMessage());
        }
    }

    protected function client(): Client
    {
        return new Client([
            'headers' => [
                'aade-user-id'              => self::$user_id,
                'Ocp-Apim-Subscription-Key' => self::$subscription_key,
                'Content-Type'              => "text/xml"
            ],
        ]);
    }

    private static function validate(): void
    {
        if (empty(self::$env)) {
            throw new Error("Missing environment value. Please invoke MyDataRequest::setEnvironment to set the environment value, possible values are [dev,prod].");
        }

        if (empty(self::$user_id) || empty(self::$subscription_key)) {
            throw new Error("Missing credentials. Please use MyDataRequest::setCredentials method to set your myDATA credentials.");
        }        
    }
}