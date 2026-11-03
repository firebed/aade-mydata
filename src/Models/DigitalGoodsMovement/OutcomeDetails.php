<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Models\Model;

/**
 * @version 2.0.1
 */
class OutcomeDetails extends Model
{
    protected array $expectedOrder = [
        'outcome',
        'deliveredWithoutRecipient',
        'deliveredPackaging',
    ];

    protected function casts(): array
    {
        return [
            'outcome' => DeliveryOutcomeType::class,
            'deliveredPackaging' => Arr::of(PackagingDetail::class),
        ];
    }

    /**
     * @return DeliveryOutcomeType|null Το αποτέλεσμα της παράδοσης
     *
     * @version 2.0.1
     */
    public function getOutcome(): ?DeliveryOutcomeType
    {
        return $this->get('outcome');
    }

    /**
     * @param  DeliveryOutcomeType  $outcome  Το αποτέλεσμα της παράδοσης
     *
     * @version 2.0.1
     */
    public function setOutcome(DeliveryOutcomeType $outcome): static
    {
        return $this->set('outcome', $outcome);
    }

    /**
     * @return bool|null Έχει τιμή true αν η παράδοση έγινε χωρίς την παρουσία του παραλήπτη
     *
     * @version 2.0.1
     */
    public function getDeliveredWithoutRecipient(): ?bool
    {
        return $this->get('deliveredWithoutRecipient');
    }

    /**
     * @param  bool  $deliveredWithoutRecipient  Ένδειξη αν η παράδοση έγινε χωρίς την παρουσία του παραλήπτη
     *
     * @version 2.0.1
     */
    public function setDeliveredWithoutRecipient(bool $deliveredWithoutRecipient): static
    {
        return $this->set('deliveredWithoutRecipient', $deliveredWithoutRecipient);
    }

    /**
     * @return PackagingDetail[]|null Λίστα με τις παραδοθείσες συσκευασίες
     *
     * @version 2.0.1
     */
    public function getDeliveredPackaging(): ?array
    {
        return $this->get('deliveredPackaging');
    }

    /**
     * @param  PackagingDetail[]  $deliveredPackaging  Λίστα με τις παραδοθείσες συσκευασίες
     *
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
}
