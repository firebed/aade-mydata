<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\HasSchemaValidation;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\Type;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryReturnWriter;

/**
 * @version 2.0.2
 */
class DeliveryReturn extends Type implements ValidatesSchema
{
    use HasSchemaValidation;

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
     * @version 2.0.2
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * @param string $qrUrl Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     * @return static
     * @version 2.0.2
     */
    public function setQrUrl(string $qrUrl): static
    {
        return $this->set('qrUrl', $qrUrl);
    }

    public function toXml(): string
    {
        return (new DeliveryReturnWriter())->asXml($this);
    }

    public function validate(): array
    {
        $xml = (new DeliveryReturnWriter())->asXml($this);

        return $this->validateSchema($xml, 'ConfirmDeliveryReturn-' . Invoice::VERSION . '.xsd');
    }
}
