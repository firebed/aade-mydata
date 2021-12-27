<?php

namespace Firebed\AadeMyData\Parser;

use Firebed\AadeMyData\Models\ResponseDoc;
use SimpleXMLElement;

class ResponseDocParser
{
    use XMLParser;

    private static array $class_map;

    public static function parseXML(SimpleXMLElement $xml): ResponseDoc
    {
        self::$class_map = require __DIR__ . '/../../config/class_map.php';

        return self::parse($xml);
    }
}