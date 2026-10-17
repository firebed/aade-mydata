<?php

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use Firebed\AadeMyData\Models\ResponseDoc;

/**
 * @extends XMLReader<ResponseDoc>
 */
class ResponseDocReader extends XMLReader
{
    protected bool $readsResponseDoc = true;

    public function parseXML(string $xmlString): ResponseDoc
    {
        $doc = new ResponseDoc();
        $this->loadXML($xmlString, $doc);

        return $doc;
    }

    /**
     * @param DOMDocument $document Loaded without whitespace nodes (preserveWhiteSpace = false)
     */
    public function parseDocument(DOMDocument $document): ResponseDoc
    {
        $doc = new ResponseDoc();
        $this->loadDocument($document, $doc);

        return $doc;
    }
}