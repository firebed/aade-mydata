<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class DeliveryNoteStatusResponse extends Type
{
    protected array $casts = [
        'status' => DeliveryStatus::class,
        'lifecycleHistory' => DeliveryEvent::class,
    ];

    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Συμβάντος (παράγεται από το myDATA)
     * @version 2.0.1
     */
    public function getInvoiceMark(): ?string
    {
        return $this->get('invoiceMark');
    }

    /**
     * @return DeliveryStatus|null Κωδικός Αποτελέσματος
     * @version 2.0.1
     */
    public function getStatus(): ?DeliveryStatus
    {
        return $this->get('status');
    }

    /**
     * @return string|null Ημερομηνία και Ώρα Εκκίνησης/Μεταφόρτωσης Διακίνησης (μορφή ISO 8601)
     * @version 2.0.1
     */
    public function getDispatchTimestamp(): ?string
    {
        return $this->get('dispatchTimestamp');
    }

    /**
     * @return DeliveryEvent[]|null Ιστορικό Γεγονότων Διακίνησης
     * @version 2.0.1
     */
    public function getLifecycleHistory(): ?array
    {
        return $this->get('lifecycleHistory');
    }

    public function set($key, $value): static
    {
        if ($key === 'lifecycleHistory' && ! is_array($value)) {
            return $this->push($key, $value);
        }

        // Workaround to account for the bug where mydata returns the
        // status as a string instead of an integer.
        if ($key === 'status' && is_string($value) && ! is_numeric($value)) {
            return $this->set($key, DeliveryStatus::tryFromName($value));
        }

        return parent::set($key, $value);
    }
}