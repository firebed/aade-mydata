<?php

namespace Firebed\AadeMyData\Xml\Statements;

use Firebed\AadeMyData\Models\Statements\ResponseStatementDoc;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<ResponseStatementDoc>
 */
class ResponseStatementDocReader extends XMLReader
{
    public function parseXML(string $xmlString): ResponseStatementDoc
    {
        $doc = new ResponseStatementDoc();
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}