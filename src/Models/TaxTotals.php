<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * Περιγράφει τη δομή των φόρων που αφορούν το σύνολο του παραστατικού.
 */
class TaxTotals extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'taxType',
        'taxCategory',
        'underlyingValue',
        'taxAmount',
        'id',
    ];

    protected array $casts = [
        'taxType' => TaxType::class,
    ];

    /**
     * @return TaxType|null Είδος Φόρου
     */
    public function getTaxType(): ?TaxType
    {
        return $this->get('taxType');
    }

    /**
     * @param TaxType|string $taxType Είδος Φόρου
     */
    public function setTaxType(TaxType|string $taxType): static
    {
        return $this->set('taxType', $taxType);
    }

    /**
     * @return WithheldPercentCategory|FeesPercentCategory|OtherTaxesPercentCategory|StampCategory|int|null Κατηγορία Φόρου
     */
    public function getTaxCategory(): WithheldPercentCategory|FeesPercentCategory|OtherTaxesPercentCategory|StampCategory|int|null
    {
        return $this->get('taxCategory');
    }

    /**
     * Το πεδίο αυτό μπορεί να πάρει κάθε φορά οποιαδήποτε τιμή
     * από τον αντίστοιχο πίνακα του Παραρτήματος του φόρου που
     * αναφέρεται στο πεδίο taxType.
     *
     * @param  WithheldPercentCategory|FeesPercentCategory|OtherTaxesPercentCategory|StampCategory|int|null  $taxCategory  Κατηγορία Φόρου
     */
    public function setTaxCategory(WithheldPercentCategory|FeesPercentCategory|OtherTaxesPercentCategory|StampCategory|int|null $taxCategory): static
    {
        return $this->set('taxCategory', $taxCategory);
    }

    /**
     * @return float|null Υποκείμενη Αξία
     */
    public function getUnderlyingValue(): ?float
    {
        return $this->get('underlyingValue');
    }

    /**
     * <ul>Υποδηλώνει την αξία στην οποία εφαρμόζεται ο συγκεκριμένος φόρος.
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param  float|null  $underlyingValue  Υποκείμενη Αξία
     */
    public function setUnderlyingValue(?float $underlyingValue): static
    {
        return $this->set('underlyingValue', $underlyingValue);
    }

    /**
     * @return float|null Ποσό Φόρου
     */
    public function getTaxAmount(): ?float
    {
        return $this->get('taxAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $taxAmount Ποσό Φόρου
     */
    public function setTaxAmount(float $taxAmount): static
    {
        return $this->set('taxAmount', $taxAmount);
    }

    /**
     * @return int|null Αύξων αριθμός γραμμής
     */
    public function getId(): ?int
    {
        return $this->get('id');
    }

    /**
     * @param  int|null  $id  Αύξων αριθμός γραμμής
     */
    public function setId(?int $id): static
    {
        return $this->set('id', $id);
    }

}