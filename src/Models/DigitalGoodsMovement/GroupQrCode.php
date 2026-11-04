<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\GroupQrCodeWriter;

/**
 * @version 2.0.1
 */
class GroupQrCode extends Model implements ValidatesSchema
{
    protected array $expectedOrder = [
        'qrUrls',
    ];

    protected function casts(): array
    {
        return [
            'qrUrls' => QrUrls::class,
        ];
    }

    /**
     * @return QrUrls|null Λίστα με τα URLs των QR codes των Δελτίων Αποστολής που περιλαμβάνονται στο Ομαδικό QR Code
     *
     * @version 2.0.1
     */
    public function getQrUrls(): ?QrUrls
    {
        return $this->get('qrUrls');
    }

    /**
     * @param  QrUrls|list<string>  $qrUrls  Λίστα με τα URLs των QR codes των Δελτίων Αποστολής που περιλαμβάνονται στο Ομαδικό QR Code
     *
     * @version 2.0.1
     */
    public function setQrUrls(QrUrls|array $qrUrls): static
    {
        return $this->set('qrUrls', $qrUrls);
    }

    public function addQrUrl(string $qrUrl): static
    {
        $qrUrls = $this->getQrUrls();
        if ($qrUrls === null) {
            $qrUrls = new QrUrls;
            $this->setQrUrls($qrUrls);
        }

        $qrUrls->add($qrUrl);

        return $this;
    }

    public function toXml(): string
    {
        return (new GroupQrCodeWriter)->asXml($this);
    }

    public function validate(): array
    {
        return (new GroupQrCodeWriter)->validate($this, 'GenerateGroupQRCode');
    }
}
