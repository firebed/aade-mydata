<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryReturnWriter;

/**
 * @version 2.0.2
 */
class DeliveryReturn extends Model implements ValidatesSchema
{
    protected array $expectedOrder = [
        'qrUrl',
    ];

    public function __construct(?string $qrUrl = null)
    {
        parent::__construct();

        if ($qrUrl !== null) {
            $this->setQrUrl($qrUrl);
        }
    }

    /**
     * @return string|null Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     *
     * @version 2.0.2
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * @param  string  $qrUrl  Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     *
     * @version 2.0.2
     */
    public function setQrUrl(string $qrUrl): static
    {
        return $this->set('qrUrl', $qrUrl);
    }

    public function toXml(): string
    {
        return (new DeliveryReturnWriter)->asXml($this);
    }

    public function validate(): array
    {
        return (new DeliveryReturnWriter)->validate($this, 'ConfirmDeliveryReturn');
    }
}
