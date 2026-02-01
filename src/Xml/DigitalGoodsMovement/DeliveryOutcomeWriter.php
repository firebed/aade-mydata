<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<Transport>
 * @version 2.0.1
 */
class DeliveryOutcomeWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $rootNode = $this->document->createElement('ConfirmDeliveryOutcomeRequest');
        $this->document->appendChild($rootNode);

        foreach ($data->sortedAttributes() as $key => $value) {
            $this->build($rootNode, $key, $value);
        }

        return $this->document->saveXML();
    }
}