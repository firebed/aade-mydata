<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\InvoicesDoc;

/**
 * @extends XMLReader<InvoicesDoc>
 */
class InvoicesDocReader extends XMLReader
{
    public function parseXML(string $xmlString): InvoicesDoc
    {
        $doc = new InvoicesDoc();
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}