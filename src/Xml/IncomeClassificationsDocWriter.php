<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\IncomeClassificationsDoc;

/**
 * @extends XMLWriter<IncomeClassificationsDoc>
 */
class IncomeClassificationsDocWriter extends XMLWriter
{
    private const string XMLNS = 'https://www.aade.gr/myDATA/incomeClassificaton/v1.0';

    private const string XSI = 'http://www.w3.org/2001/XMLSchema-instance';
    //    private const ICLS            = 'https://www.aade.gr/myDATA/incomeClassificaton/v1.0';
    //    private const ECLS            = 'https://www.aade.gr/myDATA/expensesClassificaton/v1.0';

    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXML($data): string
    {
        $rootNode = $this->document->createElementNS(self::XMLNS, 'IncomeClassificationsDoc');
        $this->document->appendChild($rootNode);

        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', self::XSI);
        //        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:icls', self::ICLS);
        //        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ecls', self::ECLS);

        $this->buildArray($rootNode, 'incomeInvoiceClassification', iterator_to_array($data));

        return Dom::save($this->document);
    }
}
