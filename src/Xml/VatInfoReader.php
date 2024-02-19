<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedVatInfo;

class VatInfoReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedVatInfo
    {
        $doc = new RequestedVatInfo();
        $this->loadXML($xmlString, $doc);
        
        return $doc;
    }
}