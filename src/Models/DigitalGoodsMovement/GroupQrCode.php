<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\HasSchemaValidation;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\Type;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\GroupQrCodeWriter;

/**
 * @version 2.0.1
 */
class GroupQrCode extends Type implements ValidatesSchema
{
    use HasSchemaValidation;

    protected array $expectedOrder = [
        'qrUrls',
    ];

    protected array $casts = [
        'qrUrls' => QrUrls::class,
    ];

    /**
     * @return QrUrls|null Λίστα με τα URLs των QR codes των Δελτίων Αποστολής που περιλαμβάνονται στο Ομαδικό QR Code
     * @version 2.0.1
     */
    public function getQrUrls(): ?QrUrls
    {
        return $this->get('qrUrls');
    }

    /**
     * @param QrUrls|array $qrUrls Λίστα με τα URLs των QR codes των Δελτίων Αποστολής που περιλαμβάνονται στο Ομαδικό QR Code
     * @return static
     * @version 2.0.1
     */
    public function setQrUrls(QrUrls|array $qrUrls): static
    {
        if (is_array($qrUrls)) {
            $qrUrls = new QrUrls($qrUrls);
        }

        return $this->set('qrUrls', $qrUrls);
    }

    public function addQrUrl(string $qrUrl): static
    {
        $qrUrls = $this->getQrUrls();
        if ($qrUrls === null) {
            $qrUrls = new QrUrls();
            $this->setQrUrls($qrUrls);
        }

        $qrUrls->add($qrUrl);

        return $this;
    }

    public function toXml(): string
    {
        return (new GroupQrCodeWriter())->asXml($this);
    }

    public function validate(): array
    {
        $xml = (new GroupQrCodeWriter())->asXml($this);

        return $this->validateSchema($xml, 'GenerateGroupQRCode-' . Invoice::VERSION . '.xsd');
    }
}