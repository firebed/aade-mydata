<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\ResponseDoc;

class ResponseDocReader extends XMLReader
{
    public function parseXML(string $xmlString): ResponseDoc
    {
        $doc = new ResponseDoc();
        $this->loadXML($xmlString, $doc);
        
        return $doc;
    }
}