<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryOutcome;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ConfirmDeliveryOutcomeTestCase extends DigitalGoodsMovementTestCase
{
    public function test_dev_erp_url_is_correct()
    {
        $this->initErpDev();

        $request = new ConfirmDeliveryOutcome();
        $this->assertEquals('https://mydataapidev.aade.gr/ConfirmDeliveryOutcome', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        $this->initErpProd();

        $request = new ConfirmDeliveryOutcome();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/ConfirmDeliveryOutcome', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_dev_provider_url_is_not_supported()
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        $request = new ConfirmDeliveryOutcome();
        $request->handle(new DeliveryOutcome());
    }

    /**
     * @throws MyDataException
     */
    public function test_prod_provider_url_is_correct()
    {
        $this->initProviderProd();

        $this->expectException(UnsupportedChannelException::class);

        $request = new ConfirmDeliveryOutcome();
        $request->handle(new DeliveryOutcome());
    }

    /**
     * @throws MyDataException
     */
    public function test_confirm_delivery_outcome(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('confirm-delivery-outcome-response.xml')),
        ]));

        $outcome = new DeliveryOutcome();
        $outcome->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url');
        $outcome->setOutcome(DeliveryOutcomeType::FULL);
        $outcome->addDeliveredPackaging(new PackagingDetail(PackagingType::CRATE, 10));
        $outcome->addDeliveredPackaging(new PackagingDetail(PackagingType::BOX, 2));

        $this->assertEmpty($outcome->validate());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('confirm-delivery-outcome-request.xml'), $outcome->toXml());

        $confirm = new ConfirmDeliveryOutcome();
        $response = $confirm->handle($outcome);

        $this->assertCount(1, $response);
        $this->assertTrue($response->first()->isSuccessful());
        $this->assertFalse($response->first()->isFailed());

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('confirm-delivery-outcome-request.xml'), $confirm->getRequestXml());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('confirm-delivery-outcome-response.xml'), $confirm->getResponseXML());
    }
}