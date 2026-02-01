<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\HasSchemaValidation;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryOutcomeWriter;

/**
 * @version 2.0.1
 */
class DeliveryOutcome extends OutcomeDetails implements ValidatesSchema
{
    use HasSchemaValidation;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        array_unshift($this->expectedOrder, 'qrUrl');
    }

    /**
     * @return string|null Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     * @version 2.0.1
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * @param string $qrUrl Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     *
     * @return static
     * @version 2.0.1
     */
    public function setQrUrl(string $qrUrl): static
    {
        return $this->set('qrUrl', $qrUrl);
    }

    public function toXml(): string
    {
        return (new DeliveryOutcomeWriter())->asXml($this);
    }

    public function validate(): array
    {
        $xml = (new DeliveryOutcomeWriter())->asXml($this);

        return $this->validateSchema($xml, 'ConfirmDeliveryOutcome-' . Invoice::VERSION . '.xsd');
    }
}