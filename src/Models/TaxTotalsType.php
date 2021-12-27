<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Models\Enums\VatType;

/**
 * Ο τύπος Σύνολα Φόρων (TaxTotalsType) περιγράφει τη δομή των φόρων που αφορούν
 * το σύνολο του παραστατικού.
 */
class TaxTotalsType extends Type
{
    /**
     * @return int Είδος Φόρου
     */
    public function getTaxType(): int
    {
        return $this->get('taxType');
    }

    /**
     * <h2>Είδος Φόρου</h2>
     *
     * <ol>Λίστα τιμών:
     * <li>Παρακρατούμενος Φόρος</li>
     * <li>Τέλη</li>
     * <li>Λοιποί Φόροι</li>
     * <li>Χαρτόσημο</li>
     * <li>Κρατήσεις</li>
     * </ol>
     *
     * @param int $taxType Είδος Φόρου
     * @return $this
     */
    public function setTaxType(int $taxType): self
    {
        return $this->put('taxType', $taxType);
    }

    /**
     * @return int|null Κατηγορία Φόρου
     */
    public function getTaxCategory(): ?int
    {
        return $this->get('taxCategory');
    }

    /**
     * <h2>Κατηγορία Φόρου</h2>
     *
     * <p>Το πεδίο αυτό μπορεί να πάρει κάθε φορά οποιαδήποτε τιμή από τον
     * αντίστοιχο πίνακα του Παραρτήματος του φόρου που αναφέρεται στο πεδίο
     * taxType.</p>
     *
     * @param int $taxCategory Ελάχιστη τιμή = 1
     * @return $this
     * @see VatType
     */
    public function setTaxCategory(int $taxCategory): self
    {
        return $this->put('taxCategory', $taxCategory);
    }

    /**
     * @return float|null Υποκείμενη Αξία
     */
    public function getUnderlyingValue(): ?float
    {
        return $this->get('underlyingValue');
    }

    /**
     * <h2>Υποκείμενη Αξία</h2>
     *
     * <p>Το πεδίο αυτό υποδηλώνει την αξία στην οποία εφαρμόζεται ο
     * συγκεκριμένος φόρος.</p>
     *
     * @param float $underlyingValue Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setUnderlyingValue(float $underlyingValue): self
    {
        return $this->put('underlyingValue', $underlyingValue);
    }

    /**
     * @return float Ποσό Φόρου
     */
    public function getTaxAmount(): float
    {
        return $this->get('taxAmount');
    }

    /**
     * <h2>Ποσό Φόρου</h2>
     *
     * @param float $taxAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setTaxAmount(float $taxAmount): self
    {
        return $this->put('taxAmount', $taxAmount);
    }

    /**
     * @return int|null Αύξων αριθμός γραμμής
     */
    public function getId(): ?int
    {
        return $this->get('id');
    }

    /**
     * <h2>Αύξων αριθμός γραμμής</h2>
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        return $this->put('id', $id);
    }
}