<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedDoc;

/**
 * @extends XMLReader<RequestedDoc>
 */
class RequestedDocReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedDoc
    {
        $doc = new RequestedDoc();
        $this->loadXML($xmlString, $doc);
        
        return $doc;
    }
}