<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Models\Enums\InvoiceType;
use Firebed\AadeMyData\Models\Enums\MovePurpose;

class InvoiceHeaderType extends Type
{
    /**
     * @return string Σειρά παραστατικού
     */
    public function getSeries(): string
    {
        return $this->get('series');
    }

    /**
     * <h2>Σειρά παραστατικού</h2>
     *
     * <p> Σε περίπτωση μή έκδοσης σειράς παραστατικού, το πεδίο series
     * πρέπει να έχει την τιμή 0.</p>
     *
     * @param string $series Μέγιστο επιτρεπτό μήκος 50
     */
    public function setSeries(string $series): self
    {
        return $this->put('series', $series);
    }

    /**
     * @return string ΑΑ Παραστατικού
     */
    public function getAa(): string
    {
        return $this->get('aa');
    }

    /**
     * <h2>ΑΑ Παραστατικού</h2>
     *
     * @param string $aa Μέγιστο επιτρεπτό μήκος 50
     */
    public function setAa(string $aa): self
    {
        return $this->put('aa', $aa);
    }

    /**
     * @return string Ημερομηνία Έκδοσης Παραστατικού
     */
    public function getIssueDate(): string
    {
        return $this->get('issueDate');
    }

    /**
     * <h2>Ημερομηνία Έκδοσης Παραστατικού</h2>
     *
     * @param string $issueDate Ημερομηνία Έκδοσης Παραστατικού
     */
    public function setIssueDate(string $issueDate): self
    {
        return $this->put('issueDate', $issueDate);
    }

    /**
     * @return string Είδος Παραστατικού
     */
    public function getInvoiceType(): string
    {
        return $this->get('invoiceType');
    }

    /**
     * <h2>Είδος Παραστατικού</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα
     * του Παραρτήματος.</p>
     *
     * @param string $invoiceType Είδος Παραστατικού
     * @see InvoiceType
     */
    public function setInvoiceType(string $invoiceType): self
    {
        return $this->put('invoiceType', $invoiceType);
    }

    /**
     * @return bool|null Αναστολή Καταβολής ΦΠΑ
     */
    public function getVatPaymentSuspension(): ?bool
    {
        return $this->get('vatPaymentSuspension');
    }

    /**
     * <h2>Αναστολή Καταβολής ΦΠΑ</h2>
     *
     * @param bool $vatPaymentSuspension Αναστολή Καταβολής ΦΠΑ
     */
    public function setVatPaymentSuspension(bool $vatPaymentSuspension): self
    {
        return $this->put('vatPaymentSuspension', $vatPaymentSuspension);
    }

    /**
     * @return string|null Νόμισμα
     */
    public function getCurrency(): ?string
    {
        return $this->get('currency');
    }

    /**
     * <h2>Νόμισμα</h2>
     *
     * <p>Ο κωδικός νομισμάτων προέρχεται από την αντίστοιχη λίστα σύμφωνα με το
     * πρότυπο ISO4217.</p>
     *
     * @param string $currency Κωδικός νομίσματος
     */
    public function setCurrency(string $currency): self
    {
        return $this->put('currency', $currency);
    }

    /**
     * @return float|null Ισοτιμία
     */
    public function getExchangeRate(): ?float
    {
        return $this->get('exchangeRate');
    }

    /**
     * <h2>Ισοτιμία</h2>
     *
     * <p>To πεδίο exchangeRate είναι η ισοτιμία του νομίσματος σε σχέση με το ευρώ.
     * Πρέπει να συμπληρώνεται μόνο όταν το νόμισμα δεν έχει τιμή EUR.</p>
     *
     * @param float $exchangeRate Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 5
     */
    public function setExchangeRate(float $exchangeRate): self
    {
        return $this->put('exchangeRate', $exchangeRate);
    }

    /**
     * @return string|null Συσχετιζόμενα Παραστατικά
     */
    public function getCorrelatedInvoices(): ?string
    {
        return $this->get('correlatedInvoices');
    }

    /**
     * <h2>Συσχετιζόμενα Παραστατικά</h2>
     *
     * <p>Το στοιχείο correlatedInvoices είναι λίστα και περιέχει τα ΜΑΡΚ των
     * συσχετιζόμενων παραστατικών.</p>
     *
     * @param string $correlatedInvoices Συσχετιζόμενα Παραστατικά
     */
    public function setCorrelatedInvoices(string $correlatedInvoices): self
    {
        return $this->put('correlatedInvoices', $correlatedInvoices);
    }

    /**
     * @return bool|null Ένδειξη Αυτοτιμολόγησης
     */
    public function isSelfPricing(): ?bool
    {
        return $this->get('selfPricing');
    }

    /**
     * <h2>Ένδειξη Αυτοτιμολόγησης</h2>
     *
     * <p>Το πεδίο selfPricing ορίζει αν πρόκειται για Τιμολόγιο Αυτοτιμολόγησης.</p>
     *
     * @param bool $selfPricing Ένδειξη Αυτοτιμολόγησης
     */
    public function setSelfPricing(bool $selfPricing): self
    {
        return $this->put('selfPricing', $selfPricing);
    }

    /**
     * @return string|null Ημερομηνία Έναρξης Αποστολής
     */
    public function getDispatchDate(): ?string
    {
        return $this->get('dispatchDate');
    }

    /**
     * <h2>Ημερομηνία Έναρξης Αποστολής</h2>
     * @param string $dispatchDate Format: Y-m-d
     */
    public function setDispatchDate(string $dispatchDate): self
    {
        return $this->put('dispatchDate', $dispatchDate);
    }

    /**
     * @return string|null Ώρα Έναρξης Αποστολής
     */
    public function getDispatchTime(): ?string
    {
        return $this->get('dispatchTime');
    }

    /**
     * Ώρα Έναρξης Αποστολής
     *
     * @param string $dispatchTime Format H:i
     */
    public function setDispatchTime(string $dispatchTime): self
    {
        return $this->put('dispatchTime', $dispatchTime);
    }

    /**
     * @return string|null Αριθμός Μεταφορικού Μέσου
     */
    public function getVehicleNumber(): ?string
    {
        return $this->get('vehicleNumber');
    }

    /**
     * <h2>Αριθμός Μεταφορικού Μέσου</h2>
     *
     * @param string $vehicleNumber Αριθμός Μεταφορικού Μέσου
     */
    public function setVehicleNumber(string $vehicleNumber): self
    {
        return $this->put('vehicleNumber', $vehicleNumber);
    }

    /**
     * @return int|null Σκοπός Διακίνησης
     */
    public function getMovePurpose(): ?int
    {
        return $this->get('movePurpose');
    }

    /**
     * <h2>Σκοπός Διακίνησης</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα
     * του Παραρτήματος.</p>
     *
     * @param int $movePurpose Σκοπός Διακίνησης
     * @see MovePurpose
     */
    public function setMovePurpose(int $movePurpose): self
    {
        return $this->put('movePurpose', $movePurpose);
    }

    /**
     * @return bool|null Ένδειξη Παραστατικό καυσίμων
     */
    public function getFuelInvoice(): ?bool
    {
        return $this->get('fuelInvoice');
    }

    /**
     * <h2>Ένδειξη Παραστατικό καυσίμων</h2>
     *
     * <p>Το πεδίο fuelInvoice ορίζει αν πρόκειται για παραστατικό πώλησης υγρών
     * καυσίμων και επιτρέπεται η αποστολή μόνο για την περίπτωση των
     * παρόχων.</p>
     *
     * @param bool $fuelInvoice Ένδειξη Παραστατικό καυσίμων
     */
    public function setFuelInvoice(bool $fuelInvoice): self
    {
        return $this->put('fuelInvoice', $fuelInvoice);
    }
}