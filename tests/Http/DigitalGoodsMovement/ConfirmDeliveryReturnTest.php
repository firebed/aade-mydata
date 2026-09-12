<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryReturn;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryReturn;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ConfirmDeliveryReturnTest extends DigitalGoodsMovementTestCase
{
    public function test_dev_erp_url_is_correct(): void
    {
        $this->initErpDev();

        $request = new ConfirmDeliveryReturn();
        $this->assertEquals('https://mydataapidev.aade.gr/ConfirmDeliveryReturn', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct(): void
    {
        $this->initErpProd();

        $request = new ConfirmDeliveryReturn();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/ConfirmDeliveryReturn', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_provider_channel_is_not_supported(): void
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        (new ConfirmDeliveryReturn())->handle(new DeliveryReturn('https://example.test/qr'));
    }

    /**
     * @throws MyDataException
     */
    public function test_confirm_delivery_return(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('confirm-delivery-return-response.xml')),
        ]));

        $return = new DeliveryReturn();
        $return->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url');

        $this->assertEmpty($return->validate());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('confirm-delivery-return-request.xml'), $return->toXml());

        $confirm = new ConfirmDeliveryReturn();
        $response = $confirm->handle($return);

        $this->assertCount(1, $response);
        $this->assertTrue($response->first()->isSuccessful());
        $this->assertSame(444444444444444, $response->first()->getDeliveryReturnMark());

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('confirm-delivery-return-request.xml'), $confirm->getRequestXml());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('confirm-delivery-return-response.xml'), $confirm->getResponseXML());
    }
}
