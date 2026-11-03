<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\Environment;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\MyData;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class MyDataClientTest extends TestCase
{
    use UsesStubs;

    protected function tearDown(): void
    {
        MyDataRequest::setDefaultConnection(null);
    }

    public function test_connect_builds_a_client_on_its_own_connection(): void
    {
        $mydata = MyData::connect('user', 'key', 'dev', true);

        $this->assertSame('user', $mydata->connection()->userId());
        $this->assertSame(Environment::Development, $mydata->connection()->environment());
        $this->assertTrue($mydata->connection()->isProvider());
    }

    public function test_requests_are_bound_to_the_client_connection(): void
    {
        $gateway = new RecordingGateway;
        $mydata = new MyData(Connection::make('user', 'key')->withGateway($gateway));

        $response = $mydata->sendInvoices(new Invoice);

        $this->assertSame('400001', $response->first()->getInvoiceMark());
        $this->assertInstanceOf(SendInvoices::class, $gateway->request);
        $this->assertSame($mydata->connection(), $gateway->request->connection());
        $this->assertSame($gateway->request, $mydata->lastRequest());
        $this->assertStringContainsString('<InvoicesDoc', $mydata->lastRequest()->getRequestXml());
    }

    public function test_request_binds_any_request(): void
    {
        $gateway = new RecordingGateway($this->getStub('request-doc-response'));
        $mydata = new MyData(Connection::make('user', 'key')->withGateway($gateway));

        $request = $mydata->request(new RequestDocs);
        $docs = $request->handle('0');

        $this->assertSame($mydata->connection(), $request->connection());
        $this->assertSame($request, $gateway->request);
        $this->assertCount(2, $docs->getInvoices());
    }

    public function test_connect_defaults_to_the_development_environment(): void
    {
        $this->assertSame(Environment::Development, MyData::connect('user', 'key')->connection()->environment());
        $this->assertSame(Environment::Development, Connection::make('user', 'key')->environment());
    }

    public function test_with_changes_the_connection_on_a_new_client(): void
    {
        $mydata = MyData::connect('user', 'key');
        $provider = $mydata->with(fn (Connection $c) => $c->asProvider()->withRetries(2));

        $this->assertNotSame($mydata, $provider);
        $this->assertFalse($mydata->connection()->isProvider());
        $this->assertTrue($provider->connection()->isProvider());
        $this->assertSame(2, $provider->connection()->retries());
    }

    public function test_two_clients_do_not_share_state(): void
    {
        $a = new MyData(Connection::make('a', 'ka')->withGateway($gatewayA = new RecordingGateway));
        $b = new MyData(Connection::make('b', 'kb')->withGateway($gatewayB = new RecordingGateway));

        $a->sendInvoices(new Invoice);
        $b->sendInvoices(new Invoice);

        $this->assertSame('a', $gatewayA->request->connection()->userId());
        $this->assertSame('b', $gatewayB->request->connection()->userId());
        $this->assertNull(MyDataRequest::defaultConnection()->handler());
        $this->assertFalse(MyDataRequest::hasCredentials());
    }
}
