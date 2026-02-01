<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<ResponseDoc>
 */
class ResponseDocReader extends XMLReader
{
    public function parseXml(string $xmlString): ResponseDoc
    {
        $responseDoc = new ResponseDoc();
        $this->loadXML($xmlString, $responseDoc);

        return $responseDoc;
    }
}