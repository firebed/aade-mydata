<?php

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedVatInfo;

class VatInfoReader extends XMLReader
{
    private const PATH_TO_CLASSMAP = __DIR__.'/../../config/class_map.php';

    public function __construct()
    {
        parent::__construct(require self::PATH_TO_CLASSMAP);
    }

    public function parseXML(string $xmlString): RequestedVatInfo
    {
        return $this->loadXML($xmlString);
    }
}