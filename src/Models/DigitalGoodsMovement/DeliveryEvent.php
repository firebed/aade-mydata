<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryEventType;
use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class DeliveryEvent extends Type
{
    protected array $expectedOrder = [
        'eventType',
        'eventTimestamp',
        'actorVat',
        'mark',
        'transportDetails',
        'outcomeDetails',
        'rejectionDetails',
    ];

    protected array $casts = [
        'eventType' => DeliveryEventType::class,
        'transportDetails' => TransportDetails::class,
        'outcomeDetails' => OutcomeDetails::class,
        'rejectionDetails' => RejectionDetails::class,
    ];

    /**
     * @return DeliveryEventType|null Ο τύπος του γεγονότος
     * @version 2.0.1
     */
    public function getEventType(): ?DeliveryEventType
    {
        return $this->get('eventType');
    }

    /**
     * @param DeliveryEventType|string $eventType Ο τύπος του γεγονότος
     *
     * @return $this
     * @version 2.0.1
     */
    public function setEventType(DeliveryEventType|string $eventType): static
    {
        return $this->set('eventType', $eventType);
    }

    /**
     * @return string|null Η χρονική σήμανση του γεγονότος.
     * @version 2.0.1
     */
    public function getEventTimestamp(): ?string
    {
        return $this->get('eventTimestamp');
    }

    /**
     * @param string|null $eventTimestamp Η χρονική σήμανση του γεγονότος (Y-m-dTH:i:s).
     *
     * @return $this
     * @version 2.0.1
     */
    public function setEventTimestamp(?string $eventTimestamp): static
    {
        return $this->set('eventTimestamp', $eventTimestamp);
    }

    /**
     * @return string|null ΑΦΜ Χρήστη που δημιούργησε το συμβάν.
     * @version 2.0.1
     */
    public function getActorVat(): ?string
    {
        return $this->get('actorVat');
    }

    /**
     * @param string|null $actorVat ΑΦΜ Χρήστη που δημιούργησε το συμβάν.
     *
     * @return $this
     * @version 2.0.1
     */
    public function setActorVat(?string $actorVat): static
    {
        return $this->set('actorVat', $actorVat);
    }

    /**
     * @return int|null Μοναδικός Αριθμός Καταχώρησης Συμβάντος (Συμπληρώνεται από την υπηρεσία)
     * @version 2.0.1
     */
    public function getMark(): ?int
    {
        return $this->get('mark');
    }

    /**
     * @return TransportDetails|null Στοιχεία Μεταφοράς
     * @version 2.0.1
     */
    public function getTransportDetails(): ?TransportDetails
    {
        return $this->get('transportDetails');
    }

    /**
     * @param TransportDetails|null $transportDetails Στοιχεία Μεταφοράς
     *
     * @return $this
     * @version 2.0.1
     */
    public function setTransportDetails(TransportDetails|null $transportDetails): static
    {
        return $this->set('transportDetails', $transportDetails);
    }

    /**
     * @return OutcomeDetails|null Λεπτομέρειες για το αποτέλεσμα της παράδοσης.
     * @version 2.0.1
     */
    public function getOutcomeDetails(): ?OutcomeDetails
    {
        return $this->get('outcomeDetails');
    }

    /**
     * @param OutcomeDetails|null $outcomeDetails Λεπτομέρειες για το αποτέλεσμα της παράδοσης.
     *
     * @return $this
     * @version 2.0.1
     */
    public function setOutcomeDetails(OutcomeDetails|null $outcomeDetails): static
    {
        return $this->set('outcomeDetails', $outcomeDetails);
    }

    /**
     * @return RejectionDetails|null Λεπτομέρειες απόρριψης παράδοσης.
     * @version 2.0.1
     */
    public function getRejectionDetails(): ?RejectionDetails
    {
        return $this->get('rejectionDetails');
    }

    /**
     * @param RejectionDetails|null $rejectionDetails Λεπτομέρειες απόρριψης παράδοσης.
     *
     * @return $this
     * @version 2.0.1
     */
    public function setRejectionDetails(RejectionDetails|null $rejectionDetails): static
    {
        return $this->set('rejectionDetails', $rejectionDetails);
    }
}