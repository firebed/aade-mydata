<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryEventType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestDeliveryNoteStatus;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RequestDeliveryNoteStatusTest extends DigitalGoodsMovementTest
{
    private function assertRegisterTransferEvent($event): void
    {
        $this->assertEquals(DeliveryEventType::REGISTER_TRANSFER, $event->getEventType());
        $this->assertEquals('2026-02-07T10:00:00Z', $event->getEventTimestamp());
        $this->assertEquals('777777777', $event->getActorVat());
        $this->assertEquals(222222222222222, $event->getMark());

        $transportDetails = $event->getTransportDetails();
        $this->assertNotNull($transportDetails);
        $this->assertEquals('AHN0011', $transportDetails->getVehicleNumber());
        $this->assertEquals(TransportType::PRIVATE_USE_TRUCK, $transportDetails->getTransportType());
        $this->assertEquals('2026-02-07T10:00:00Z', $transportDetails->getTimestamp());
        $this->assertEquals('777777777', $transportDetails->getCarrierVatNumber());
        $this->assertEquals('P22345', $transportDetails->getTrailorNumber());

        $location = $transportDetails->getLocation();
        $this->assertNotNull($location);
        $this->assertEquals(41.303921, $location->getLongitude());
        $this->assertEquals(-81.901693, $location->getLatitude());
    }

    private function assertConfirmOutcomeEvent($event, string $actorVat, int $mark, DeliveryOutcomeType $outcome, bool $hasPackaging = true): void
    {
        $this->assertEquals(DeliveryEventType::CONFIRM_OUTCOME, $event->getEventType());
        $this->assertEquals($actorVat, $event->getActorVat());
        $this->assertEquals($mark, $event->getMark());

        $outcomeDetails = $event->getOutcomeDetails();
        $this->assertNotNull($outcomeDetails);
        $this->assertEquals($outcome, $outcomeDetails->getOutcome());
        $this->assertFalse($outcomeDetails->getDeliveredWithoutRecipient());

        if ($hasPackaging) {
            $deliveredPackaging = $outcomeDetails->getDeliveredPackaging();
            $this->assertIsArray($deliveredPackaging);
            $this->assertCount(2, $deliveredPackaging);
            $this->assertEquals(PackagingType::CRATE, $deliveredPackaging[0]->getPackagingType());
            $this->assertEquals(10, $deliveredPackaging[0]->getQuantity());
            $this->assertEquals(PackagingType::BOX, $deliveredPackaging[1]->getPackagingType());
            $this->assertEquals(2, $deliveredPackaging[1]->getQuantity());
        }
    }

    public function test_dev_erp_url_is_correct()
    {
        $this->initErpDev();

        $request = new RequestDeliveryNoteStatus();
        $this->assertEquals('https://mydataapidev.aade.gr/GetDeliveryNoteStatus', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        $this->initErpProd();

        $request = new RequestDeliveryNoteStatus();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/GetDeliveryNoteStatus', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_dev_provider_url_is_not_supported()
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RequestDeliveryNoteStatus();
        $request->handle(123);
    }

    /**
     * @throws MyDataException
     */
    public function test_prod_provider_url_is_correct()
    {
        $this->initProviderProd();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RequestDeliveryNoteStatus();
        $request->handle(123);
    }

    /**
     * @throws MyDataException
     */
    public function test_request_delivery_note_status_registered(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-registered.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();
        $response = $request->handle(111111111111111);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-delivery-note-status-response-registered.xml'), $request->getResponseXML());

        $this->assertEquals(111111111111111, $response->getInvoiceMark());
        $this->assertEquals(DeliveryStatus::REGISTERED, $response->getStatus());
    }

    /**
     * @throws MyDataException
     */
    public function test_request_delivery_note_status_in_transit(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-in-transit.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();
        $response = $request->handle(111111111111111);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-delivery-note-status-response-in-transit.xml'), $request->getResponseXML());
        $this->assertEquals(111111111111111, $response->getInvoiceMark());
        $this->assertEquals(DeliveryStatus::IN_TRANSIT, $response->getStatus());
        $this->assertEquals('2026-02-07T10:00:00Z', $response->getDispatchTimestamp());

        $lifecycleHistory = $response->getLifecycleHistory();
        $this->assertCount(1, $lifecycleHistory);
        $this->assertRegisterTransferEvent($lifecycleHistory[0]);
    }

    /**
     * @throws MyDataException
     */
    public function test_request_delivery_note_status_delivered_by_carrier(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-delivered-by-carrier.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();
        $response = $request->handle(111111111111111);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-delivery-note-status-response-delivered-by-carrier.xml'), $request->getResponseXML());
        $this->assertEquals(111111111111111, $response->getInvoiceMark());
        $this->assertEquals(DeliveryStatus::DELIVERED_BY_CARRIER, $response->getStatus());
        $this->assertEquals('2026-02-07T10:00:00Z', $response->getDispatchTimestamp());

        $lifecycleHistory = $response->getLifecycleHistory();
        $this->assertCount(2, $lifecycleHistory);
        $this->assertRegisterTransferEvent($lifecycleHistory[0]);
        $this->assertConfirmOutcomeEvent($lifecycleHistory[1], '777777777', 333333333333333, DeliveryOutcomeType::FULL);
    }

    /**
     * @throws MyDataException
     */
    public function test_request_delivery_note_status_rejected(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-rejected.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();
        $response = $request->handle(111111111111111);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-delivery-note-status-response-rejected.xml'), $request->getResponseXML());
        $this->assertEquals(111111111111111, $response->getInvoiceMark());
        $this->assertEquals(DeliveryStatus::REJECTED, $response->getStatus());
        $this->assertEquals('2026-02-07T10:00:00Z', $response->getDispatchTimestamp());

        $lifecycleHistory = $response->getLifecycleHistory();
        $this->assertCount(3, $lifecycleHistory);
        $this->assertRegisterTransferEvent($lifecycleHistory[0]);
        $this->assertConfirmOutcomeEvent($lifecycleHistory[1], '777777777', 333333333333333, DeliveryOutcomeType::PARTIAL);

        // Rejection event
        $this->assertEquals(DeliveryEventType::REJECTION, $lifecycleHistory[2]->getEventType());
        $this->assertEquals('2026-02-07T12:00:00Z', $lifecycleHistory[2]->getEventTimestamp());
        $this->assertEquals('888888888', $lifecycleHistory[2]->getActorVat());
        $this->assertEquals(444444444444444, $lifecycleHistory[2]->getMark());
    }

    /**
     * @throws MyDataException
     */
    public function test_request_delivery_note_status_completed(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-completed.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();
        $response = $request->handle(111111111111111);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-delivery-note-status-response-completed.xml'), $request->getResponseXML());
        $this->assertEquals(111111111111111, $response->getInvoiceMark());
        $this->assertEquals(DeliveryStatus::COMPLETED, $response->getStatus());
        $this->assertEquals('2026-02-07T10:00:00Z', $response->getDispatchTimestamp());

        $lifecycleHistory = $response->getLifecycleHistory();
        $this->assertCount(3, $lifecycleHistory);
        $this->assertRegisterTransferEvent($lifecycleHistory[0]);
        $this->assertConfirmOutcomeEvent($lifecycleHistory[1], '777777777', 333333333333333, DeliveryOutcomeType::PARTIAL);
        $this->assertConfirmOutcomeEvent($lifecycleHistory[2], '888888888', 444444444444444, DeliveryOutcomeType::PARTIAL);
    }

    /**
     * @throws MyDataException
     */
    public function test_request_delivery_note_status_failed_delivery(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-failed-delivery.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();
        $response = $request->handle(111111111111111);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-delivery-note-status-response-failed-delivery.xml'), $request->getResponseXML());
        $this->assertEquals(111111111111111, $response->getInvoiceMark());
        $this->assertEquals(DeliveryStatus::FAILED_DELIVERY, $response->getStatus());
        $this->assertEquals('2026-02-07T10:00:00Z', $response->getDispatchTimestamp());

        $lifecycleHistory = $response->getLifecycleHistory();
        $this->assertCount(2, $lifecycleHistory);
        $this->assertRegisterTransferEvent($lifecycleHistory[0]);
        $this->assertConfirmOutcomeEvent($lifecycleHistory[1], '777777777', 333333333333333, DeliveryOutcomeType::NONE, false);
    }
}