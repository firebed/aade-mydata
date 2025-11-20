<?php

namespace Firebed\AadeMyData\Xml\Statements;

use Firebed\AadeMyData\Models\Statements\StatementDoc;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<StatementDoc>
 */
class StatementDocWriter extends XMLWriter
{
    private const XSI             = 'http://www.w3.org/2001/XMLSchema-instance';

    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXML($data): string
    {
        // Root element without explicit namespace (schema does not define targetNamespace)
        $rootNode = $this->document->createElement('StatementDoc');
        $this->document->appendChild($rootNode);

        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', self::XSI);

        // Build statement node
        $this->build($rootNode, 'statement', $data->get('statement'));

        return $this->document->saveXML();
    }
}
