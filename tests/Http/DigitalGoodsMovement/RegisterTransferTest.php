<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RegisterTransfer;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Location;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\TransportDetails;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RegisterTransferTest extends DigitalGoodsMovementTest
{
    public function test_dev_erp_url_is_correct()
    {
        $this->initErpDev();

        $request = new RegisterTransfer();
        $this->assertEquals('https://mydataapidev.aade.gr/RegisterTransfer', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        $this->initErpProd();

        $request = new RegisterTransfer();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/RegisterTransfer', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_dev_provider_url_is_not_supported()
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RegisterTransfer();
        $request->handle(new Transport());
    }

    /**
     * @throws MyDataException
     */
    public function test_prod_provider_url_is_correct()
    {
        $this->initProviderProd();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RegisterTransfer();
        $request->handle(new Transport());
    }

    /**
     * @throws MyDataException
     */
    public function test_register_transfer(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('register-transfer-response')),
        ]));

        $details = new TransportDetails();
        $details->setVehicleNumber('AHN0011');
        $details->setTransportType(TransportType::PRIVATE_USE_TRUCK);
        $details->setCarrierVatNumber('777777777');
        $details->setTrailorNumber('P22345');
        $details->setLocation(new Location(41.303921, -81.901693));

        $transport = new Transport();
        $transport->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_qr_url');
        $transport->setTransportDetail($details);

        $this->assertEmpty($transport->validate());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('register-transfer-request.xml'), $transport->toXml());

        $registerTransfer = new RegisterTransfer();
        $response = $registerTransfer->handle($transport);

        $this->assertCount(1, $response);
        $this->assertTrue($response->first()->isSuccessful());
        $this->assertFalse($response->first()->isFailed());

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('register-transfer-request.xml'), $registerTransfer->getRequestXml());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('register-transfer-response.xml'), $registerTransfer->getResponseXML());
    }
}