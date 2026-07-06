<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\CancelReceivingNote;
use Firebed\AadeMyData\Http\MyDataRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class CancelReceivingNoteTest extends MyDataHttpTestCase
{
    public function test_dev_provider_url_is_correct(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev', true);
        $request = new CancelReceivingNote();
        $this->assertEquals('https://mydataapidev.aade.gr/myDataProvider/CancelReceivingNote', $request->getUrl());
    }

    public function test_prod_provider_url_is_correct(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'prod', true);
        $request = new CancelReceivingNote();
        $this->assertEquals('https://mydatapi.aade.gr/myDataProvider/CancelReceivingNote', $request->getUrl());
    }

    /**
     * @throws MyDataException
     */
    public function test_erp_channel_is_not_supported(): void
    {
        $this->initErpDev();

        $this->expectException(UnsupportedChannelException::class);

        (new CancelReceivingNote())->handle('400008989888809', '123456789');
    }

    /**
     * @throws MyDataException
     */
    public function test_receiving_note_is_cancelled(): void
    {
        $this->initProviderDev();

        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('cancel-receiving-note-response')),
        ]));

        $request = new CancelReceivingNote();
        $responseDoc = $request->handle('400008989888809', '123456789');

        $this->assertCount(1, $responseDoc);
        $this->assertEquals('Success', $responseDoc->first()->getStatusCode());
    }
}
