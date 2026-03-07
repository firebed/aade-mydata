<?php

namespace Tests\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\GenerateGroupQrCode;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class GenerateGroupQrCodeTest extends DigitalGoodsMovementTestCase
{
    public function test_dev_erp_url_is_correct()
    {
        $this->initErpDev();

        $request = new GenerateGroupQrCode();
        $this->assertEquals('https://mydataapidev.aade.gr/GenerateGroupQRCode', $request->getUrl());
    }

    public function test_prod_erp_url_is_correct()
    {
        $this->initErpProd();

        $request = new GenerateGroupQrCode();
        $this->assertEquals('https://mydatapi.aade.gr/myDATA/GenerateGroupQRCode', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_dev_provider_url_is_not_supported()
    {
        $this->initProviderDev();

        $this->expectException(UnsupportedChannelException::class);

        $request = new GenerateGroupQrCode();
        $request->handle(new GroupQrCode());
    }

    /**
     * @throws MyDataException
     */
    public function test_prod_provider_url_is_not_supported()
    {
        $this->initProviderProd();

        $this->expectException(UnsupportedChannelException::class);

        $request = new GenerateGroupQrCode();
        $request->handle(new GroupQrCode());
    }

    /**
     * @throws MyDataException
     */
    public function test_generate_group_qr_code(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('generate-group-qr-code-response.xml')),
        ]));

        $groupQrCode = new GroupQrCode();
        $groupQrCode->addQrUrl("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_1");
        $groupQrCode->addQrUrl("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_2");

        $this->assertEmpty($groupQrCode->validate());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('generate-group-qr-code-request.xml'), $groupQrCode->toXml());

        $generate = new GenerateGroupQrCode();
        $response = $generate->handle($groupQrCode);

        $this->assertEquals('https://mydataapidev.aade.gr/TimologioQR/GroupQRInfo?groupId=9946C6AA9DB3658C85CC3BF43DB726FE25BBC555', $response->getGroupQrUrl());
        $this->assertEquals('9946C6AA9DB3658C85CC3BF43DB726FE25BBC555', $response->getGroupId());
        $this->assertEquals(2, $response->getQrUrlsCount());
        $this->assertEquals('2025-02-08T12:00:00', $response->getExpiresAt());
        $this->assertEquals('Success', $response->getStatusCode());

        $this->assertXmlStringEqualsXmlFile($this->stubsPath('generate-group-qr-code-request.xml'), $generate->getRequestXml());
        $this->assertXmlStringEqualsXmlFile($this->stubsPath('generate-group-qr-code-response.xml'), $generate->getResponseXML());
    }

    /**
     * @throws MyDataException
     */
    public function test_generate_from_qr_urls_helper_method(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('generate-group-qr-code-response.xml')),
        ]));

        $qrUrls = [
            "https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_1",
            "https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_2",
        ];

        $generate = new GenerateGroupQrCode();
        $response = $generate->generateFromQrUrls($qrUrls);

        $this->assertEquals('https://mydataapidev.aade.gr/TimologioQR/GroupQRInfo?groupId=9946C6AA9DB3658C85CC3BF43DB726FE25BBC555', $response->getGroupQrUrl());
        $this->assertEquals(2, $response->getQrUrlsCount());
        $this->assertEquals('Success', $response->getStatusCode());
    }
}
