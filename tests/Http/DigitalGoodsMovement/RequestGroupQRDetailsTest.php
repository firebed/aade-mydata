<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\ErrorResponseException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestGroupQrDetails;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class RequestGroupQRDetailsTest extends DigitalGoodsMovementTestCase
{
    public function test_dev_erp_url_is_correct()
    {
        $this->initErpDev();

        $request = new RequestGroupQrDetails();
        $this->assertEquals('https://mydataapidev.aade.gr/RequestGroupQRDetails', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        $this->initErpProd();

        $request = new RequestGroupQrDetails();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/RequestGroupQRDetails', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_dev_provider_url_is_not_supported()
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RequestGroupQrDetails();
        $request->handle('test');
    }

    /**
     * @throws MyDataException
     */
    public function test_prod_provider_url_is_not_supported()
    {
        $this->initProviderProd();

        $this->expectException(UnsupportedChannelException::class);

        $request = new RequestGroupQrDetails();
        $request->handle('test');
    }

    /**
     * @throws MyDataException
     */
    public function test_request_group_qr_details(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-group-qr-details-response.xml')),
        ]));

        $request = new RequestGroupQrDetails();
        $response = $request->handle('9946C6AA9DB3658C85CC3BF43DB726FE25BBC555');

        $this->assertEquals('9946C6AA9DB3658C85CC3BF43DB726FE25BBC555', $response->getGroupId());
        $this->assertEquals(2, $response->getQrUrlsCount());
        $this->assertCount(2, $response->getQrUrls());
        $this->assertEquals('123456789', $response->getGroupQrCreatorVatNumber());
        $this->assertEquals('2025-02-07T10:00:00', $response->getCreatedAt());
        $this->assertEquals('2025-02-08T12:00:00', $response->getExpiresAt());
        $this->assertEquals('Success', $response->getStatusCode());

        $qrUrls = $response->getQrUrls();
        $this->assertEquals("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_1", $qrUrls[0]);
        $this->assertEquals("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_2", $qrUrls[1]);

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('request-group-qr-details-response.xml'), $request->getResponseXML());
    }

    /**
     * @throws MyDataException
     */
    public function test_error_response_throws_an_exception(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('error-response-807.xml')),
        ]));

        $this->expectException(ErrorResponseException::class);
        $this->expectExceptionCode(807);
        $this->expectExceptionMessage('807: Invalid QR');
        (new RequestGroupQrDetails())->handle('test');
    }

    /**
     * @throws MyDataException
     */
    public function test_error_without_a_code_reports_only_its_message(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: '<ResponseDoc><response><statusCode>ValidationError</statusCode><errors><error><message>Invalid request: groupId must be a valid 40-character SHA1 hash</message></error></errors></response></ResponseDoc>'),
        ]));

        $this->expectException(ErrorResponseException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageMatches('/^Invalid request: groupId must be a valid 40-character SHA1 hash$/');
        (new RequestGroupQrDetails())->handle('test');
    }
}
