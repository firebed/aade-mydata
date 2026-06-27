<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryReturn;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<DeliveryReturn>
 * @version 2.0.2
 */
class DeliveryReturnWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $rootNode = $this->document->createElement('ConfirmDeliveryReturnRequest');
        $this->document->appendChild($rootNode);

        foreach ($data->sortedAttributes() as $key => $value) {
            $this->build($rootNode, $key, $value);
        }

        return $this->document->saveXML();
    }
}
