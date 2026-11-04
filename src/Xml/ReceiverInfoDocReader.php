<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use Firebed\AadeMyData\Models\ReceiverInfoDoc;

/**
 * @extends XMLReader<ReceiverInfoDoc>
 */
class ReceiverInfoDocReader extends XMLReader
{
    public function parseXML(string $xmlString): ReceiverInfoDoc
    {
        $doc = new ReceiverInfoDoc;
        $this->loadXML($xmlString, $doc);

        return $doc;
    }
}
