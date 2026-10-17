<?php

namespace Tests\Http\DigitalGoodsMovement;

use ErrorException;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryEventType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;
use Firebed\AadeMyData\Exceptions\ErrorResponseException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestDeliveryNoteStatus;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class RequestDeliveryNoteStatusTest extends DigitalGoodsMovementTestCase
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

    /**
     * @throws MyDataException
     */
    public function test_status_lookup_by_qr_url_builds_qr_url_query(): void
    {
        $mock = new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-registered.xml')),
        ]);
        MyDataRequest::setHandler($mock);

        $request = new RequestDeliveryNoteStatus();
        // The deprecated issuerVatNumber is not sent: myDATA rejects it together with qrUrl (HTTP 400)
        $response = $request->handleUsingQrUrl('https://mydataapidev.aade.gr/qr?q=abc', issuerVatNumber: '123456789');

        $query = Query::parse($mock->getLastRequest()->getUri()->getQuery());
        $this->assertSame(['qrUrl'], array_keys($query));

        $this->assertEquals(DeliveryStatus::REGISTERED, $response->getStatus());
    }

    /**
     * @throws MyDataException
     */
    public function test_status_lookup_by_qr_url_does_not_encode_the_qr_url_twice(): void
    {
        $mock = new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-registered.xml')),
        ]);
        MyDataRequest::setHandler($mock);

        // myDATA hands out QR URLs that are already percent-encoded
        (new RequestDeliveryNoteStatus())->handleUsingQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=POiz%2fab%2bc%3d');

        $query = $mock->getLastRequest()->getUri()->getQuery();
        $this->assertStringNotContainsString('%25', $query);
        $this->assertSame('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=POiz/ab+c=', Query::parse($query)['qrUrl']);
    }

    /**
     * @throws MyDataException
     */
    public function test_error_response_throws_an_exception(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('error-response-807.xml')),
        ]));

        $request = new RequestDeliveryNoteStatus();

        try {
            $request->handleUsingQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=abc');
            $this->fail('An ErrorResponseException was expected.');
        } catch (ErrorResponseException $e) {
            $this->assertSame('807: Invalid QR', $e->getMessage());
            $this->assertSame(807, $e->getCode());
            $this->assertSame('807', $e->getErrors()->first()->getCode());
            $this->assertStringContainsString('<ResponseDoc', $request->getResponseXML());
        }
    }

    public static function errorDocumentsWithoutErrors(): array
    {
        return [
            'no responses' => ['<ResponseDoc/>'],
            'no errors' => ['<ResponseDoc><response><statusCode>ValidationError</statusCode></response></ResponseDoc>'],
        ];
    }

    /**
     * @throws MyDataException
     */
    #[DataProvider('errorDocumentsWithoutErrors')]
    public function test_an_error_document_without_errors_still_throws_cleanly(string $body): void
    {
        MyDataRequest::setHandler(new MockHandler([new Response(200, body: $body)]));

        // Fail on any warning, as frameworks that turn warnings into exceptions would
        set_error_handler(fn (int $no, string $message) => throw new ErrorException($message, 0, $no));
        try {
            (new RequestDeliveryNoteStatus())->handle(111111111111111);
            $this->fail('An ErrorResponseException was expected.');
        } catch (ErrorResponseException $e) {
            $this->assertSame('myDATA returned an error response.', $e->getMessage());
            $this->assertSame(0, $e->getCode());
            $this->assertCount(0, $e->getErrors());
        } finally {
            restore_error_handler();
        }
    }

    public function test_response_xml_is_null_before_a_request_is_made(): void
    {
        $request = new RequestDeliveryNoteStatus();

        $this->assertNull($request->getResponseXML());
    }

    /**
     * @throws MyDataException
     */
    public function test_status_lookup_by_mark_builds_mark_query(): void
    {
        $mock = new MockHandler([
            new Response(200, body: $this->getStub('request-delivery-note-status-response-registered.xml')),
        ]);
        MyDataRequest::setHandler($mock);

        (new RequestDeliveryNoteStatus())->handle(111111111111111);

        $query = $mock->getLastRequest()->getUri()->getQuery();
        $this->assertStringContainsString('mark=111111111111111', $query);
        $this->assertStringNotContainsString('qrUrl=', $query);
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