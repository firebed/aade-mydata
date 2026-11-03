<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\RequestedE3Info;

/**
 * @extends XMLReader<RequestedE3Info>
 *
 * @version 1.0.10
 */
class E3InfoReader extends XMLReader
{
    public function parseXML(string $xmlString): RequestedE3Info
    {
        $doc = new RequestedE3Info;
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}
