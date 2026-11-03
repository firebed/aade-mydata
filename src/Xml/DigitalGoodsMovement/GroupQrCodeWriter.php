<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;
use Firebed\AadeMyData\Xml\Dom;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<GroupQrCode>
 *
 * @version 2.0.1
 */
class GroupQrCodeWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $this->buildModel($this->document, 'GenerateGroupQRCodeRequest', $data);

        return Dom::save($this->document);
    }
}
