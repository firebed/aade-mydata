<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class OutcomeDetails extends Type
{
    protected array $expectedOrder = [
        'outcome',
        'deliveredWithoutRecipient',
        'deliveredPackaging',
    ];

    protected array $casts = [
        'outcome' => DeliveryOutcomeType::class,
        'deliveredPackaging' => PackagingDetail::class,
    ];

    /**
     * @return DeliveryOutcomeType|null Το αποτέλεσμα της παράδοσης
     * @version 2.0.1
     */
    public function getOutcome(): ?DeliveryOutcomeType
    {
        return $this->get('outcome');
    }

    /**
     * @param DeliveryOutcomeType $outcome Το αποτέλεσμα της παράδοσης
     * @return static
     * @version 2.0.1
     */
    public function setOutcome(DeliveryOutcomeType $outcome): static
    {
        return $this->set('outcome', $outcome);
    }

    /**
     * @return PackagingDetail|null Έχει τιμή true αν η παράδοση έγινε χωρίς την παρουσία του παραλήπτη
     * @version 2.0.1
     */
    public function getDeliveredWithoutRecipient(): ?bool
    {
        return $this->get('deliveredWithoutRecipient');
    }

    /**
     * @param bool $deliveredWithoutRecipient Ένδειξη αν η παράδοση έγινε χωρίς την παρουσία του παραλήπτη
     * @return static
     * @version 2.0.1
     */
    public function setDeliveredWithoutRecipient(bool $deliveredWithoutRecipient): static
    {
        return $this->set('deliveredWithoutRecipient', $deliveredWithoutRecipient);
    }

    /**
     * @return PackagingDetail[]|null Λίστα με τις παραδοθείσες συσκευασίες
     * @version 2.0.1
     */
    public function getDeliveredPackaging(): ?array
    {
        return $this->get('deliveredPackaging');
    }

    /**
     * @param PackagingDetail[]|null $deliveredPackaging Λίστα με τις παραδοθείσες συσκευασίες
     * @return static
     * @version 2.0.1
     */
    public function setDeliveredPackaging(array $deliveredPackaging): static
    {
        return $this->set('deliveredPackaging', $deliveredPackaging);
    }

    public function addDeliveredPackaging(PackagingDetail $packagingDetail): static
    {
        return $this->push('deliveredPackaging', $packagingDetail);
    }

    public function set($key, $value): static
    {
        if ($key === 'deliveredPackaging' && ! is_array($value)) {
            return $this->push('deliveredPackaging', $value);
        }

        return parent::set($key, $value);
    }


}