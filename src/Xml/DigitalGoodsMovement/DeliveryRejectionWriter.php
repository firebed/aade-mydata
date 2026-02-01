<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<DeliveryRejection>
 * @version 2.0.1
 */
class DeliveryRejectionWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $rootNode = $this->document->createElement('RejectDeliveryNoteRequest');
        $this->document->appendChild($rootNode);

        foreach ($data->sortedAttributes() as $key => $value) {
            $this->build($rootNode, $key, $value);
        }

        return $this->document->saveXML();
    }
}