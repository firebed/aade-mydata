<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\InvoicesDoc;

class InvoicesDocWriter extends XMLWriter
{
    private const DOC_VERSION     = 'v1.0.8';
    
    private const XMLNS           = 'http://www.aade.gr/myDATA/invoice/v1.0';
    private const XSI             = 'http://www.w3.org/2001/XMLSchema-instance';
    private const SCHEMA_LOCATION = "http://www.aade.gr/myDATA/invoice/v1.0/InvoicesDoc-" . self::DOC_VERSION.".xsd";
    private const ICLS            = 'https://www.aade.gr/myDATA/incomeClassificaton/v1.0';
    private const ECLS            = 'https://www.aade.gr/myDATA/expensesClassificaton/v1.0';

    protected array $namespaces = [
        'incomeClassification'   => ['*' => self::ICLS],
        'expensesClassification' => ['*' => self::ECLS],
    ];

    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXML(InvoicesDoc $invoicesDoc): string
    {
        $rootNode = $this->document->createElementNS(self::XMLNS, 'InvoicesDoc');
        $this->document->appendChild($rootNode);
        
        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', self::XSI);
        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:icls', self::ICLS);
        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ecls', self::ECLS);
        $rootNode->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', self::SCHEMA_LOCATION);
        
        $this->buildArray($rootNode, 'invoice', iterator_to_array($invoicesDoc));
        
        return $this->document->saveXML();
    }
}
