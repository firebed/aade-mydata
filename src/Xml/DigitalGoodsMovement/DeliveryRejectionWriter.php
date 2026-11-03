<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use Firebed\AadeMyData\Xml\Dom;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<DeliveryRejection>
 *
 * @version 2.0.1
 */
class DeliveryRejectionWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $this->buildModel($this->document, 'RejectDeliveryNoteRequest', $data);

        return Dom::save($this->document);
    }
}
