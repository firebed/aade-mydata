<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryReturn;
use Firebed\AadeMyData\Xml\Dom;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<DeliveryReturn>
 *
 * @version 2.0.2
 */
class DeliveryReturnWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $this->buildModel($this->document, 'ConfirmDeliveryReturnRequest', $data);

        return Dom::save($this->document);
    }
}
