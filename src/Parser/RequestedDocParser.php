<?php

namespace Firebed\AadeMyData\Parser;

use SimpleXMLElement;

class RequestedDocParser
{
    use XMLParser;

    private static array $class_map;

    public static function parseXML(SimpleXMLElement $xml): mixed
    {
        self::$class_map = require __DIR__ . '/../../config/class_map.php';

        $xml->registerXPathNamespace('ecls', 'https://www.aade.gr/myDATA/expensesClassificaton/v1.0');
        $xml->registerXPathNamespace('icls', 'https://www.aade.gr/myDATA/incomeClassificaton/v1.0');

        return self::parse($xml);
    }
    
}