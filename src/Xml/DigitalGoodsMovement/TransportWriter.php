<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Xml\Dom;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<Transport>
 *
 * @version 2.0.1
 */
class TransportWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $this->buildModel($this->document, 'Transport', $data);

        return Dom::save($this->document);
    }
}
