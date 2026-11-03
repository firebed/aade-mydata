<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\InvoicesDoc;

/**
 * @extends XMLWriter<InvoicesDoc>
 */
class InvoicesDocWriter extends XMLWriter
{
    private const string XMLNS = 'http://www.aade.gr/myDATA/invoice/v1.0';

    private const string XSI = 'http://www.w3.org/2001/XMLSchema-instance';

    private const string ICLS = 'https://www.aade.gr/myDATA/incomeClassificaton/v1.0';

    private const string ECLS = 'https://www.aade.gr/myDATA/expensesClassificaton/v1.0';

    protected array $namespaces = [
        'incomeClassification' => ['*' => self::ICLS],
        'expensesClassification' => ['*' => self::ECLS],
    ];

    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXML($data): string
    {
        $rootNode = $this->document->createElementNS(self::XMLNS, 'InvoicesDoc');
        $this->document->appendChild($rootNode);

        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', self::XSI);
        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:icls', self::ICLS);
        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ecls', self::ECLS);

        $this->buildArray($rootNode, 'invoice', iterator_to_array($data));

        return Dom::save($this->document);
    }
}
