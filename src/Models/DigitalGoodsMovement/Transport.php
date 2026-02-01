<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\HasSchemaValidation;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\Type;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\TransportWriter;

/**
 * @version 2.0.1
 */
class Transport extends Type implements ValidatesSchema
{
    use HasSchemaValidation;

    protected array $expectedOrder = [
        'qrUrl',
        'transportDetail',
    ];

    protected array $casts = [
        'transportDetail' => TransportDetails::class,
    ];

    /**
     * @return TransportDetails|null Μοναδικός Αριθμός Καταχώρησης του γεγονότος μεταφοράς. Συμπληρώνεται από την υπηρεσία.
     * @version 2.0.1
     */
    public function getTransferMark(): ?int
    {
        return $this->get('transferMark');
    }

    /**
     * @return TransportDetails|null Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code.
     * @version 2.0.1
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * @param string $qrUrl Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code.
     * @return static
     * @version 2.0.1
     */
    public function setQrUrl(string $qrUrl): static
    {
        return $this->set('qrUrl', $qrUrl);
    }

    /**
     * @return TransportDetails|null Λεπτομέρειες της μεταφοράς.
     * @version 2.0.1
     */
    public function getTransportDetail(): ?TransportDetails
    {
        return $this->get('transportDetail');
    }

    /**
     * @param TransportDetails $transportDetail Λεπτομέρειες της μεταφοράς.
     * @return static
     * @version 2.0.1
     */
    public function setTransportDetail(TransportDetails $transportDetail): static
    {
        return $this->set('transportDetail', $transportDetail);
    }

    public function toXml(): string
    {
        return (new TransportWriter())->asXml($this);
    }

    public function validate(): array
    {
        $xml = (new TransportWriter())->asXml($this);

        return $this->validateSchema($xml, 'RegisterTransfer-' . Invoice::VERSION . '.xsd');
    }
}