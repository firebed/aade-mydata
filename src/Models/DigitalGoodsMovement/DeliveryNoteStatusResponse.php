<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Models\Model;

/**
 * @version 2.0.1
 */
class DeliveryNoteStatusResponse extends Model
{
    protected function casts(): array
    {
        return [
            'status' => DeliveryStatus::class,
            'lifecycleHistory' => Arr::of(DeliveryEvent::class),
        ];
    }

    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Συμβάντος (παράγεται από το myDATA)
     *
     * @version 2.0.1
     */
    public function getInvoiceMark(): ?string
    {
        return $this->get('invoiceMark');
    }

    /**
     * @return DeliveryStatus|null Κωδικός Αποτελέσματος
     *
     * @version 2.0.1
     */
    public function getStatus(): ?DeliveryStatus
    {
        return $this->get('status');
    }

    /**
     * @return string|null Ημερομηνία και Ώρα Εκκίνησης/Μεταφόρτωσης Διακίνησης (μορφή ISO 8601)
     *
     * @version 2.0.1
     */
    public function getDispatchTimestamp(): ?string
    {
        return $this->get('dispatchTimestamp');
    }

    /**
     * @return DeliveryEvent[]|null Ιστορικό Γεγονότων Διακίνησης
     *
     * @version 2.0.1
     */
    public function getLifecycleHistory(): ?array
    {
        return $this->get('lifecycleHistory');
    }
}
