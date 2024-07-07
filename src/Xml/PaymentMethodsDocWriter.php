<?php

namespace Firebed\AadeMyData\Xml;

/**
 * @extends XMLWriter<array>
 */
class PaymentMethodsDocWriter extends XMLWriter
{
    private const XMLNS = 'https://www.aade.gr/myDATA/paymentMethod/v1.0';
    private const XSI   = 'http://www.w3.org/2001/XMLSchema-instance';
    private const INV   = "http://www.aade.gr/myDATA/invoice/v1.0";

    protected array $namespaces = [
        'paymentMethodDetails' => ['*' => self::INV],
        'ECRToken'             => [
            ''  => self::INV,
            '*' => self::INV
        ],
        'ProvidersSignature'   => [
            '' => self::INV,
            '*' => self::INV,
        ],
    ];

    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXML($data): string
    {
        $rootNode = $this->document->createElementNS(self::XMLNS, 'PaymentMethodsDoc');
        $this->document->appendChild($rootNode);

        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', self::XSI);
        $rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:inv', self::INV);

        $this->buildArray($rootNode, 'paymentMethods', $data);

        return $this->document->saveXML();
    }
}
