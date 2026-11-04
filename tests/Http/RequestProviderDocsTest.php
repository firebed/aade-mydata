<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestProviderDocs;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use Firebed\AadeMyData\Models\ProviderInvoice;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\FakeGateway;
use Firebed\AadeMyData\Testing\RecordedRequest;

class RequestProviderDocsTest extends MyDataHttpTestCase
{
    private const string LAST_PAGE = '<RequestedProviderDoc><InvoiceProviderType><issuerVAT>123456789</issuerVAT><invoiceProviderMark>900000000000104</invoiceProviderMark><invoiceUid>0000000000000000000000000000000000000A04</invoiceUid><authenticationCode>000000000000000000000000000000000000AC04</authenticationCode></InvoiceProviderType></RequestedProviderDoc>';

    private MyData $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new MyData(Connection::make('u', 'k', 'dev', provider: true));
    }

    protected function tearDown(): void
    {
        MyData::stopFaking();
        MyDataRequest::setDefaultConnection(null);
    }

    public function test_it_calls_the_provider_request_transmitted_docs(): void
    {
        $this->initProviderProd();

        $this->assertSame('https://mydatapi.aade.gr/myDataProvider/RequestTransmittedDocs', (new RequestProviderDocs)->getUrl());
    }

    public function test_it_reads_the_mark_uid_and_authentication_code_of_each_invoice(): void
    {
        $fake = MyData::fake([RequestProviderDocs::class => $this->getStub('requested-provider-doc')]);

        $doc = $this->provider->requestProviderDocs('123456789', 900000000000100);

        $invoices = $doc->getInvoices();
        $this->assertCount(3, $invoices);
        $this->assertSame('123456789', $invoices[0]->getIssuerVAT());
        $this->assertSame(900000000000101, $invoices[0]->getInvoiceProviderMark());
        $this->assertSame('0000000000000000000000000000000000000A01', $invoices[0]->getInvoiceUid());
        $this->assertSame('000000000000000000000000000000000000AC01', $invoices[0]->getAuthenticationCode());
        $this->assertSame('900000000000103', $doc->getContinuationToken()?->getNextRowKey());

        $fake->assertSent(RequestProviderDocs::class, fn (RequestProviderDocs $r, RecordedRequest $sent) => $sent->query === ['issuervat' => '123456789', 'mark' => 900000000000100]);
    }

    public function test_find_by_uid_returns_every_mark_of_a_uid(): void
    {
        MyData::fake([RequestProviderDocs::class => $this->getStub('requested-provider-doc')]);

        $doc = $this->provider->requestProviderDocs('123456789');

        // The stub registers this uid twice, as myDATA does for two simultaneous submissions
        $twice = $doc->findByUid('0000000000000000000000000000000000000a02');
        $this->assertSame([900000000000102, 900000000000103], array_map(fn (ProviderInvoice $i) => $i->getInvoiceProviderMark(), $twice));
        $this->assertSame([], $doc->findByUid('0000000000000000000000000000000000000000'));
    }

    public function test_pages_follow_the_continuation_token(): void
    {
        $fake = MyData::fake([RequestProviderDocs::class => FakeGateway::sequence($this->getStub('requested-provider-doc'), self::LAST_PAGE)]);

        $marks = array_map(
            fn (ProviderInvoice $invoice) => $invoice->getInvoiceProviderMark(),
            iterator_to_array($this->provider->request(new RequestProviderDocs)->invoices('123456789', 900000000000100), false),
        );

        $this->assertSame([900000000000101, 900000000000102, 900000000000103, 900000000000104], $marks);
        $fake->assertSentCount(2);
        $fake->assertSent(RequestProviderDocs::class, fn (RequestProviderDocs $r, RecordedRequest $sent) => ($sent->query['nextRowKey'] ?? null) === '900000000000103');
    }

    public function test_an_empty_answer_has_no_invoices(): void
    {
        MyData::fake();

        $this->assertSame([], $this->provider->requestProviderDocs('123456789')->getInvoices());
    }

    public function test_it_is_provider_only(): void
    {
        $this->expectException(UnsupportedChannelException::class);

        (new MyData(Connection::make('u', 'k')))->requestProviderDocs('123456789');
    }

    public function test_the_erp_document_searches_are_erp_only(): void
    {
        MyData::fake();

        foreach ([RequestTransmittedDocs::class, RequestDocs::class] as $class) {
            try {
                $this->provider->request(new $class)->handle('0');
                $this->fail("$class ran on the provider channel.");
            } catch (UnsupportedChannelException $e) {
                $this->assertStringContainsString('ERP route', $e->getMessage());
            }
        }
    }
}
