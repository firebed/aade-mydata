<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrDetailsResponse;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<GroupQrDetailsResponse>
 */
class GroupQrDetailsReader extends XMLReader
{
    public function parseXml(string $xmlString): GroupQrDetailsResponse
    {
        $responseDoc = new GroupQrDetailsResponse();
        $this->loadXML($xmlString, $responseDoc);

        return $responseDoc;
    }
}