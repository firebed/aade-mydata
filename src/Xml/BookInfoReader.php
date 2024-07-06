<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedBookInfo;

/**
 * @extends XMLReader<RequestedBookInfo>
 */
class BookInfoReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedBookInfo
    {
        $doc = new RequestedBookInfo();
        $this->loadXML($xmlString, $doc);
        
        return $doc;
    }
}