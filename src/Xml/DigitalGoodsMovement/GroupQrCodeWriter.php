<?php

namespace Firebed\AadeMyData\Xml\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;
use Firebed\AadeMyData\Xml\XMLWriter;

/**
 * @extends XMLWriter<GroupQrCode>
 * @version 2.0.1
 */
class GroupQrCodeWriter extends XMLWriter
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function asXml($data): string
    {
        $rootNode = $this->document->createElement('GenerateGroupQRCodeRequest');
        $this->document->appendChild($rootNode);

        foreach ($data->sortedAttributes() as $key => $value) {
            $this->build($rootNode, $key, $value);
        }

        return $this->document->saveXML();
    }
}