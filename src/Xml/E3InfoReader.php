<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedE3Info;
use Firebed\AadeMyData\Models\RequestedVatInfo;

/**
 * @extends XMLReader<RequestedVatInfo>
 *     
 * @version 1.0.10
 */
class E3InfoReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedE3Info
    {
        $doc = new RequestedE3Info();
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}