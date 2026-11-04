<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedProviderDoc;

/**
 * @extends XMLReader<RequestedProviderDoc>
 */
class RequestedProviderDocReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedProviderDoc
    {
        $doc = new RequestedProviderDoc;
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}
