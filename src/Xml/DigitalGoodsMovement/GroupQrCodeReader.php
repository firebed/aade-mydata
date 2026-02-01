<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCodeResponse;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<GroupQrCodeResponse>
 */
class GroupQrCodeReader extends XMLReader
{
    public function parseXml(string $xmlString): GroupQrCodeResponse
    {
        $responseDoc = new GroupQrCodeResponse();
        $this->loadXML($xmlString, $responseDoc);

        return $responseDoc;
    }
}