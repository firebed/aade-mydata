<?php

namespace Firebed\AadeMyData\Xml\Statements;

use Firebed\AadeMyData\Models\Statements\StatementResponseDoc;
use Firebed\AadeMyData\Xml\XMLReader;

/**
 * @extends XMLReader<StatementResponseDoc>
 */
class StatementResponseDocReader extends XMLReader
{
    protected bool $readsResponseDoc = true;

    public function parseXML(string $xmlString): StatementResponseDoc
    {
        $doc = new StatementResponseDoc();
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}