<?php

namespace Tests;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\Response;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;
use PHPUnit\Framework\TestCase;

class ResponseDeliveryReturnMarkTest extends TestCase
{
    public function test_getter_returns_delivery_return_mark(): void
    {
        $response = new Response();
        $response->set('deliveryReturnMark', '400123');

        $this->assertSame(400123, $response->getDeliveryReturnMark());
    }

    public function test_reader_parses_delivery_return_mark(): void
    {
        $xml = '<ResponseDoc><response><index>1</index>'
            . '<deliveryReturnMark>400123</deliveryReturnMark>'
            . '<statusCode>Success</statusCode></response></ResponseDoc>';

        $doc = (new ResponseDocReader())->parseXml($xml);

        $this->assertSame(400123, $doc->first()->getDeliveryReturnMark());
    }
}
