<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Xml\Dom;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<DeliveryOutcome>
 *
 * @version 2.0.1
 */
class DeliveryOutcomeWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $this->buildModel($this->document, 'ConfirmDeliveryOutcomeRequest', $data);

        return Dom::save($this->document);
    }
}
