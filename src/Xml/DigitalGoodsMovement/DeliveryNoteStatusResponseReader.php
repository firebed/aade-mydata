<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryNoteStatusResponse;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<DeliveryNoteStatusResponse>
 * @version 2.0.1
 */
class DeliveryNoteStatusResponseReader extends XMLReader
{
    public function parseXml(string $xmlString): DeliveryNoteStatusResponse
    {
        $response = new DeliveryNoteStatusResponse();
        $this->loadXML($xmlString, $response);

        return $response;
    }
}