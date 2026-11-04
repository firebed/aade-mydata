<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\ValidatesSchema;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryRejectionWriter;
use Firebed\AadeMyData\Xml\Schema;

class DeliveryRejection extends Model implements ValidatesSchema
{
    protected array $expectedOrder = [
        'qrUrl',
        'invoiceMark',
        'rejectionReason',
    ];

    public function __construct(string|int|null $qrUrlOrMark = null, ?string $rejectionReason = null)
    {
        parent::__construct();

        if (is_numeric($qrUrlOrMark)) {
            $this->setInvoiceMark((int) $qrUrlOrMark);
        } elseif (is_string($qrUrlOrMark)) {
            $this->setQrUrl($qrUrlOrMark);
        }

        if ($rejectionReason !== null) {
            $this->setRejectionReason($rejectionReason);
        }
    }

    /**
     * @return string|null Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     *
     * @version 2.0.1
     */
    public function getQrUrl(): ?string
    {
        return $this->get('qrUrl');
    }

    /**
     * @param  string  $qrUrl  Το URL του QR code του Δελτίου Αποστολής ή του Ομαδικού QR Code
     *
     * @version 2.0.1
     */
    public function setQrUrl(string $qrUrl): static
    {
        return $this->set('qrUrl', $qrUrl);
    }

    /**
     * @return int|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getInvoiceMark(): ?int
    {
        return $this->get('invoiceMark');
    }

    /**
     * @param  int  $mark  Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function setInvoiceMark(int $mark): static
    {
        return $this->set('invoiceMark', $mark);
    }

    /**
     * @return string|null Αιτιολογία απόρριψης
     *
     * @version 2.0.1
     */
    public function getRejectionReason(): ?string
    {
        return $this->get('rejectionReason');
    }

    /**
     * @param  string  $rejectionReason  Αιτιολογία απόρριψης
     *
     * @version 2.0.1
     */
    public function setRejectionReason(string $rejectionReason): static
    {
        return $this->set('rejectionReason', $rejectionReason);
    }

    public function toXml(): string
    {
        return (new DeliveryRejectionWriter)->asXml($this);
    }

    public function validate(): array
    {
        $xml = (new DeliveryRejectionWriter)->asXml($this);

        return Schema::validate($xml, 'RejectDeliveryNote');

    }
}
