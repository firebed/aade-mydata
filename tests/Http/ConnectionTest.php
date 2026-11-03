<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\Environment;
use Firebed\AadeMyData\Http\GuzzleGateway;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    protected function tearDown(): void
    {
        MyDataRequest::setDefaultConnection(null);
    }

    public function test_make_builds_a_development_erp_connection_unless_told_otherwise(): void
    {
        $connection = Connection::make('user', 'key');

        $this->assertSame('user', $connection->userId());
        $this->assertSame('key', $connection->subscriptionKey());
        $this->assertSame(Environment::Development, $connection->environment());
        $this->assertTrue($connection->isDevelopment());
        $this->assertFalse($connection->isProvider());
        $this->assertTrue($connection->hasCredentials());
        $this->assertSame('https://mydataapidev.aade.gr/SendInvoices', $connection->url('SendInvoices'));

        $production = Connection::make('user', 'key', 'prod');
        $this->assertTrue($production->isProduction());
        $this->assertSame('https://mydatapi.aade.gr/myDATA/SendInvoices', $production->url('SendInvoices'));
    }

    public function test_environment_accepts_strings_in_any_case(): void
    {
        $this->assertSame(Environment::Development, Connection::make('u', 'k', 'DEV')->environment());
        $this->assertSame(Environment::Production, Connection::make('u', 'k', 'prod')->environment());

        $this->expectException(InvalidArgumentException::class);
        Connection::make('u', 'k', 'staging');
    }

    public function test_urls_per_environment_and_channel(): void
    {
        $connection = Connection::make('u', 'k', 'dev');

        $this->assertSame('https://mydataapidev.aade.gr/SendInvoices', $connection->url('SendInvoices'));
        $this->assertSame('https://mydataapidev.aade.gr/myDataProvider/SendInvoices', $connection->asProvider()->url('SendInvoices'));
        $this->assertSame('https://mydatapi.aade.gr/myDataProvider/SendInvoices', $connection->asProvider()->withEnvironment(Environment::Production)->url('SendInvoices'));
    }

    public function test_with_methods_return_changed_copies(): void
    {
        $connection = Connection::make('u', 'k');
        $changed = $connection->withTimeout(30)->withConnectionTimeout(5)->withVerify(false)->withRetries(3, 100);

        $this->assertNotSame($connection, $changed);
        $this->assertSame([], $connection->requestOptions());
        $this->assertSame(0, $connection->retries());
        $this->assertSame(['verify' => false, 'connect_timeout' => 5, 'timeout' => 30], $changed->requestOptions());
        $this->assertSame(3, $changed->retries());
        $this->assertSame(100, $changed->retryDelay());
        $this->assertSame('u', $changed->userId());
    }

    public function test_request_options_can_be_replaced_as_a_whole(): void
    {
        $connection = Connection::make('u', 'k')->withTimeout(30)->withRequestOptions(['proxy' => 'tcp://localhost:8125']);

        $this->assertSame(['proxy' => 'tcp://localhost:8125'], $connection->requestOptions());
    }

    public function test_http_client_is_built_once_per_connection(): void
    {
        $connection = Connection::make('u', 'k')->withTimeout(30);

        $client = $connection->httpClient();
        $this->assertSame($client, $connection->httpClient());
        $this->assertSame(30, $client->getConfig('timeout'));
        $this->assertSame('u', $client->getConfig('headers')['aade-user-id']);
        $this->assertSame('k', $client->getConfig('headers')['ocp-apim-subscription-key']);

        // A changed copy gets a client of its own
        $this->assertNotSame($client, $connection->withTimeout(10)->httpClient());
    }

    public function test_custom_headers_are_added_to_the_authentication_headers(): void
    {
        $client = Connection::make('u', 'k')->withRequestOption('headers', ['User-Agent' => 'erp/1.0'])->httpClient();

        $this->assertSame([
            'aade-user-id' => 'u',
            'ocp-apim-subscription-key' => 'k',
            'Content-Type' => 'text/xml',
            'User-Agent' => 'erp/1.0',
        ], $client->getConfig('headers'));
    }

    public function test_gateway_defaults_to_guzzle_and_can_be_replaced(): void
    {
        $connection = Connection::make('u', 'k');
        $this->assertInstanceOf(GuzzleGateway::class, $connection->gateway());

        $gateway = new RecordingGateway;
        $this->assertSame($gateway, $connection->withGateway($gateway)->gateway());
        $this->assertInstanceOf(GuzzleGateway::class, $connection->withGateway($gateway)->withGateway(null)->gateway());
    }

    public function test_a_request_uses_the_connection_it_is_given(): void
    {
        MyDataRequest::init('default-user', 'default-key', 'dev');

        $gateway = new RecordingGateway;
        $tenant = Connection::make('tenant-user', 'tenant-key', 'prod', true)->withGateway($gateway);

        $request = (new SendInvoices)->using($tenant);

        $this->assertSame($tenant, $request->connection());
        $this->assertSame('https://mydatapi.aade.gr/myDataProvider/SendInvoices', $request->getUrl());

        $request->handle(new Invoice);
        $this->assertSame($request, $gateway->request);

        // The default connection is untouched
        $this->assertSame('default-user', MyDataRequest::defaultConnection()->userId());
        $this->assertSame('https://mydataapidev.aade.gr/SendInvoices', (new SendInvoices)->getUrl());
        $this->assertSame(MyDataRequest::defaultConnection(), (new SendInvoices)->using(null)->connection());
    }

    public function test_the_static_setters_configure_the_default_connection(): void
    {
        MyDataRequest::init('user', 'key', 'prod', true);
        MyDataRequest::setTimeout(20);
        MyDataRequest::setConnectionTimeout(3);
        MyDataRequest::verifyClient(false);
        MyDataRequest::setRetries(2, 50);
        MyDataRequest::setHandler(new MockHandler);

        $default = MyDataRequest::defaultConnection();

        $this->assertSame('user', $default->userId());
        $this->assertTrue($default->isProduction());
        $this->assertTrue($default->isProvider());
        $this->assertTrue(MyDataRequest::isProduction());
        $this->assertTrue(MyDataRequest::isProvider());
        $this->assertTrue(MyDataRequest::hasCredentials());
        $this->assertSame(['verify' => false, 'connect_timeout' => 3, 'timeout' => 20], $default->requestOptions());
        $this->assertSame(2, $default->retries());
        $this->assertSame(50, $default->retryDelay());
        $this->assertInstanceOf(HandlerStack::class, $default->handler());

        MyDataRequest::setHandler(null);
        $this->assertNull(MyDataRequest::defaultConnection()->handler());
    }
}
