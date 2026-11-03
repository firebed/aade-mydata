<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedVatInfo;

/**
 * @extends XMLReader<RequestedVatInfo>
 */
class VatInfoReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedVatInfo
    {
        $doc = new RequestedVatInfo;
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}
