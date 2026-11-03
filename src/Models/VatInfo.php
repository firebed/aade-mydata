<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.8
 */
class VatInfo extends Model
{
    /**
     * @return string|null Το ΜΑΡΚ του παραστατικού
     *
     * @version 1.0.8
     */
    public function getMark(): ?string
    {
        return $this->get('Mark');
    }

    /**
     * @return bool Αν το παραστατικό είναι ακυρωμένο
     *
     * @version 1.0.8
     */
    public function isCancelled(): bool
    {
        return filter_var($this->get('IsCancelled'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return string Ημερομηνία έκδοσης παραστατικού
     *
     * @version 1.0.8
     */
    public function getIssueDate(): string
    {
        return $this->get('IssueDate');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 301
     *
     * @version 1.0.8
     */
    public function getVat301(): ?float
    {
        return $this->get('Vat301');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 302
     *
     * @version 1.0.8
     */
    public function getVat302(): ?float
    {
        return $this->get('Vat302');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 303
     *
     * @version 1.0.8
     */
    public function getVat303(): ?float
    {
        return $this->get('Vat303');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 304
     *
     * @version 1.0.8
     */
    public function getVat304(): ?float
    {
        return $this->get('Vat304');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 305
     *
     * @version 1.0.8
     */
    public function getVat305(): ?float
    {
        return $this->get('Vat305');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 306
     *
     * @version 1.0.8
     */
    public function getVat306(): ?float
    {
        return $this->get('Vat306');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 331
     *
     * @version 1.0.8
     */
    public function getVat331(): ?float
    {
        return $this->get('Vat331');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 332
     *
     * @version 1.0.8
     */
    public function getVat332(): ?float
    {
        return $this->get('Vat332');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 333
     *
     * @version 1.0.8
     */
    public function getVat333(): ?float
    {
        return $this->get('Vat333');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 334
     *
     * @version 1.0.8
     */
    public function getVat334(): ?float
    {
        return $this->get('Vat334');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 335
     *
     * @version 1.0.8
     */
    public function getVat335(): ?float
    {
        return $this->get('Vat335');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 336
     *
     * @version 1.0.8
     */
    public function getVat336(): ?float
    {
        return $this->get('Vat336');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 361
     *
     * @version 1.0.8
     */
    public function getVat361(): ?float
    {
        return $this->get('Vat361');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 362
     *
     * @version 1.0.8
     */
    public function getVat362(): ?float
    {
        return $this->get('Vat362');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 363
     *
     * @version 1.0.8
     */
    public function getVat363(): ?float
    {
        return $this->get('Vat363');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 364
     *
     * @version 1.0.8
     */
    public function getVat364(): ?float
    {
        return $this->get('Vat364');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 365
     *
     * @version 1.0.8
     */
    public function getVat365(): ?float
    {
        return $this->get('Vat365');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 366
     *
     * @version 1.0.8
     */
    public function getVat366(): ?float
    {
        return $this->get('Vat366');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 381
     *
     * @version 1.0.8
     */
    public function getVat381(): ?float
    {
        return $this->get('Vat381');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 382
     *
     * @version 1.0.8
     */
    public function getVat382(): ?float
    {
        return $this->get('Vat382');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 383
     *
     * @version 1.0.8
     */
    public function getVat383(): ?float
    {
        return $this->get('Vat383');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 384
     *
     * @version 1.0.8
     */
    public function getVat384(): ?float
    {
        return $this->get('Vat384');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 385
     *
     * @version 1.0.8
     */
    public function getVat385(): ?float
    {
        return $this->get('Vat385');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 386
     *
     * @version 1.0.8
     */
    public function getVat386(): ?float
    {
        return $this->get('Vat386');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 342
     *
     * @version 1.0.8
     */
    public function getVat342(): ?float
    {
        return $this->get('Vat342');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 345
     *
     * @version 1.0.8
     */
    public function getVat345(): ?float
    {
        return $this->get('Vat345');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 348
     *
     * @version 1.0.8
     */
    public function getVat348(): ?float
    {
        return $this->get('Vat348');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 349
     *
     * @version 1.0.8
     */
    public function getVat349(): ?float
    {
        return $this->get('Vat349');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 310
     *
     * @version 1.0.8
     */
    public function getVat310(): ?float
    {
        return $this->get('Vat310');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 402
     *
     * @version 1.0.8
     */
    public function getVat402(): ?float
    {
        return $this->get('Vat402');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 407
     *
     * @version 1.0.8
     */
    public function getVat407(): ?float
    {
        return $this->get('Vat407');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 411
     *
     * @version 1.0.8
     */
    public function getVat411(): ?float
    {
        return $this->get('Vat411');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 423
     *
     * @version 1.0.8
     */
    public function getVat423(): ?float
    {
        return $this->get('Vat423');
    }

    /**
     * Ποσό ΦΠΑ πεδίου 422
     *
     * @version 1.0.8
     */
    public function getVat422(): ?float
    {
        return $this->get('Vat422');
    }

    /**
     * @return float|null Ποσό ΦΠΑ πεδίου 361 μη χαρακτηρισμένα
     */
    public function getVatUnclassified361(): ?float
    {
        return $this->get('VatUnclassified361');
    }

    /**
     * @return float|null Ποσό ΦΠΑ πεδίου 381 μη χαρακτηρισμένα
     */
    public function getVatUnclassified381(): ?float
    {
        return $this->get('VatUnclassified381');
    }
}
