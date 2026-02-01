<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RejectDeliveryNote;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RejectDeliveryNoteTest extends DigitalGoodsMovementTest
{
    public function test_dev_erp_url_is_correct()
    {
        $this->initErpDev();

        $request = new RejectDeliveryNote();
        $this->assertEquals('https://mydataapidev.aade.gr/RejectDeliveryNote', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        $this->initErpProd();

        $request = new RejectDeliveryNote();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/RejectDeliveryNote', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_dev_provider_url_is_not_supported()
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RejectDeliveryNote();
        $request->handle(new DeliveryRejection());
    }

    /**
     * @throws MyDataException
     */
    public function test_prod_provider_url_is_correct()
    {
        $this->initProviderProd();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RejectDeliveryNote();
        $request->handle(new DeliveryRejection());
    }

    /**
     * @throws MyDataException
     */
    public function test_reject_delivery_note_by_mark(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('reject-delivery-note-response.xml')),
        ]));

        $rejection = new DeliveryRejection(111111111111111, 'Damaged goods');

        $this->assertEmpty($rejection->validate());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('reject-delivery-note-by-mark.xml'), $rejection->toXml());

        $reject = new RejectDeliveryNote();
        $response = $reject->handle($rejection);

        $this->assertCount(1, $response);
        $this->assertEquals(1, $response->first()->getIndex());
        $this->assertEquals('444444444444444', $response->first()->getRejectMark());
        $this->assertEquals('Success', $response->first()->getStatusCode());
        $this->assertTrue($response->first()->isSuccessful());
        $this->assertFalse($response->first()->isFailed());

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('reject-delivery-note-by-mark.xml'), $reject->getRequestXml());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('reject-delivery-note-response.xml'), $reject->getResponseXML());
    }

    /**
     * @throws MyDataException
     */
    public function test_reject_delivery_note_by_qr_url(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('reject-delivery-note-response.xml')),
        ]));

        $rejection = new DeliveryRejection('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url', 'Damaged goods');

        $this->assertEmpty($rejection->validate());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('reject-delivery-note-by-qr-url.xml'), $rejection->toXml());

        $reject = new RejectDeliveryNote();
        $response = $reject->handle($rejection);

        $this->assertCount(1, $response);
        $this->assertEquals(1, $response->first()->getIndex());
        $this->assertEquals('444444444444444', $response->first()->getRejectMark());
        $this->assertEquals('Success', $response->first()->getStatusCode());
        $this->assertTrue($response->first()->isSuccessful());
        $this->assertFalse($response->first()->isFailed());

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('reject-delivery-note-by-qr-url.xml'), $reject->getRequestXml());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('reject-delivery-note-response.xml'), $reject->getResponseXML());
    }

    /**
     * @throws MyDataException
     */
    public function test_reject_using_mark_helper_method(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('reject-delivery-note-response.xml')),
        ]));

        $reject = new RejectDeliveryNote();
        $response = $reject->rejectUsingMark(400002969517846, 'Damaged goods');

        $this->assertCount(1, $response);
        $this->assertEquals(1, $response->first()->getIndex());
        $this->assertEquals('444444444444444', $response->first()->getRejectMark());
        $this->assertEquals('Success', $response->first()->getStatusCode());
        $this->assertTrue($response->first()->isSuccessful());
    }

    /**
     * @throws MyDataException
     */
    public function test_reject_using_qr_url_helper_method(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('reject-delivery-note-response.xml')),
        ]));

        $reject = new RejectDeliveryNote();
        $response = $reject->rejectUsingQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url', 'Damaged goods');

        $this->assertCount(1, $response);
        $this->assertEquals(1, $response->first()->getIndex());
        $this->assertEquals('444444444444444', $response->first()->getRejectMark());
        $this->assertEquals('Success', $response->first()->getStatusCode());
        $this->assertTrue($response->first()->isSuccessful());
    }
}
