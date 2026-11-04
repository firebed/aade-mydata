<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\RequestReceiverInfo;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\RecordedRequest;

class RequestReceiverInfoTest extends MyDataHttpTestCase
{
    protected function tearDown(): void
    {
        MyData::stopFaking();
    }

    public function test_it_reads_the_providers_and_reception_emails(): void
    {
        $fake = MyData::fake([RequestReceiverInfo::class => $this->getStub('receiver-info-doc')]);

        $info = (new MyData(Connection::make('u', 'k', 'dev', provider: true)))->requestReceiverInfo('123456789');

        $this->assertSame(['800000001', '800000002'], $info->getReceptionProviders()?->vatNumbers());
        $this->assertSame('800000001', $info->getReceptionProviders()?->first()?->getProviderVATNumber());
        $this->assertSame(['invoices@example.com'], $info->getReceptionEmails()?->all());

        $fake->assertSent(RequestReceiverInfo::class, fn (RequestReceiverInfo $r, RecordedRequest $sent) => $sent->query === ['vatNumber' => '123456789']);
        $this->assertSame('https://mydataapidev.aade.gr/myDataProvider/RequestReceiverInfo', $fake->recorded()[0]->request->getUrl());
    }

    public function test_an_entity_without_providers_or_emails_has_none(): void
    {
        MyData::fake();

        $info = (new MyData(Connection::make('u', 'k', 'dev', provider: true)))->requestReceiverInfo('123456789');

        $this->assertNull($info->getReceptionProviders());
        $this->assertNull($info->getReceptionEmails());
    }

    public function test_it_is_provider_only(): void
    {
        $this->expectException(UnsupportedChannelException::class);

        (new MyData(Connection::make('u', 'k')))->requestReceiverInfo('123456789');
    }
}
