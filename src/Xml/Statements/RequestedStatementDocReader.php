<?php

namespace Firebed\AadeMyData\Xml\Statements;

use Firebed\AadeMyData\Models\Statements\RequestedStatementDoc;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<RequestedStatementDoc>
 */
class RequestedStatementDocReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedStatementDoc
    {
        $doc = new RequestedStatementDoc();
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}
