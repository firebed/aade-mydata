<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Models\Enums\FuelCode;

class InvoiceRowType extends Type
{
    /**
     * @return int ΑΑ γραμμής
     */
    public function getLineNumber(): int
    {
        return $this->get('lineNumber');
    }

    /**
     * <h2>ΑΑ γραμμής</h2>
     *
     * @param string $lineNumber Ελάχιστη τιμή = 1
     */
    public function setLineNumber(string $lineNumber): self
    {
        return $this->put('lineNumber', $lineNumber);
    }

    /**
     * @return int|null Είδος Γραμμής
     */
    public function getRecType(): ?int
    {
        return $this->get('recType');
    }

    /**
     * <h2>Είδος Γραμμής</h2>
     *
     * <p>Στην περίπτωση αποστολής γραμμών με recType = 2 (γραμμή τέλους με ΦΠΑ)
     * ή/και recType = 3 (Γραμμή Λοιπών Φόρων με Φ.Π.Α.), θα επιτρέπεται παράλληλα,
     * εφόσον είναι επιθυμητό, η αποστολή παρακρατούμενων / τελών / λοιπών φόρων /
     * χαρτοσήμου / κρατήσεων και σε επίπεδο παραστατικού και όχι υποχρεωτικά ανά
     * γραμμή σύνοψης παραστατικού.</p>
     *
     * <p>Στις περιπτώσεις αυτών των γραμμών, τα ποσά που αντιστοιχούν στα τέλη με ΦΠΑ
     * (recType = 2) ή τους λοιπούς φόρους (recType = 3) αντίστοιχα, θα αποστέλλονται
     * στο πεδίο της καθαρής αξίας της γραμμής (netValue), ενώ τα αντίστοιχα πεδία
     * ποσό τέλους (feesAmount) ή ποσό λοιπών φόρων (otherTaxesAmount) δε θα συμπληρώνονται.
     *
     * <p>Επίσης στις γραμμές αυτές δεν επιτρέπεται η αποστολή άλλων ειδών
     * φόρων/τελών/κρατήσεων/χαρτοσήμου (π.χ. σε μια γραμμή με recType = 2 δεν επιτρέπονται
     * στη γραμμή αυτή η αποστολή λοιπών φόρων/κρατήσεων/παρακρατούμενων/χαρτοσήμου).</p>
     *
     * @param int $recType  Στην παρούσα έκδοση οι τιμές 1, 4 και 5 δε θα μπορούν να χρησιμοποιηθούν
     *                      – έχουν δεσμευτεί στο μοντέλο για μελλοντική χρήση.
     *                      Ελάχιστη τιμή = 1, Μέγιστη τιμή = 6
     */
    public function setRecType(int $recType): self
    {
        return $this->put('recType', $recType);
    }

    /**
     * @return int|null Κωδικός Καυσίμου
     */
    public function getFuelCode(): ?int
    {
        return $this->get('fuelCode');
    }

    /**
     * <h2>Κωδικός Καυσίμου</h2>
     *
     * <p>Οι πιθανές τιμές για το πεδίο fuelCode (κωδικός καυσίμου) περιγράφονται
     * αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος. Επιτρέπεται η αποστολή του
     * μόνο για την περίπτωση των παρόχων και εφόσον πρόκειται για παραστατικό
     * καυσίμων (invoiceHeaderType.fuelInvoice = true)</p>
     *
     * <p>Αναλυτικά οι τιμές στο αντίστοιχο παράρτημα.</p>
     *
     * @param int $fuelCode Κωδικός Καυσίμου
     * @see FuelCode
     */
    public function setFuelCode(int $fuelCode): self
    {
        return $this->put('fuelCode', $fuelCode);
    }

    /**
     * @return float|null Ποσότητα
     */
    public function getQuantity(): ?float
    {
        return $this->get('quantity');
    }

    /**
     * <h2>Ποσότητα</h2>
     *
     * @param float $quantity Ελάχιστη τιμή = 0
     */
    public function setQuantity(float $quantity): self
    {
        return $this->put('quantity', $quantity);
    }

    /**
     * @return int|null Είδος Ποσότητας
     */
    public function getMeasurementUnit(): ?int
    {
        return $this->get('measurementUnit');
    }

    /**
     * <h2>Είδος Ποσότητας</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $measurementUnit Λίστα τιμών: 1, 2, 3
     */
    public function setMeasurementUnit(int $measurementUnit): self
    {
        return $this->put('measurementUnit', $measurementUnit);
    }

    /**
     * @return int|null Επισήμανση
     */
    public function getInvoiceDetailType(): ?int
    {
        return $this->get('invoiceDetailType');
    }

    /**
     * <h2>Επισήμανση</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $invoiceDetailType Λίστα τιμών: 1, 2
     */
    public function setInvoiceDetailType(int $invoiceDetailType): self
    {
        return $this->put('invoiceDetailType', $invoiceDetailType);
    }

    /**
     * @return float Καθαρή αξία
     */
    public function getNetValue(): float
    {
        return $this->get('netValue');
    }

    /**
     * <h2>Καθαρή αξία</h2>
     *
     * @param float $netValue Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setNetValue(float $netValue): self
    {
        return $this->put('netValue', $netValue);
    }

    /**
     * @return int Κατηγορία ΦΠΑ
     */
    public function getVatCategory(): int
    {
        return $this->get('vatCategory');
    }

    /**
     * <h2>Κατηγορία ΦΠΑ</h2>
     *
     * <p>Για περιπτώσεις λογιστικών εγγραφών όπου δεν εφαρμόζεται ΦΠΑ, το πεδίο
     * αυτό θα έχει την τιμή 8.</p>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $vatCategory Ελάχιστη τιμή = 1, Μέγιστη τιμή = 8
     */
    public function setVatCategory(int $vatCategory): self
    {
        return $this->put('vatCategory', $vatCategory);
    }

    /**
     * @return float Ποσό ΦΠΑ
     */
    public function getVatAmount(): float
    {
        return $this->get('vatAmount');
    }

    /**
     * <h2>Ποσό ΦΠΑ</h2>
     *
     * @param float $vatAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setVatAmount(float $vatAmount): self
    {
        return $this->put('vatAmount', $vatAmount);
    }

    /**
     * @return int|null Κατηγορία Αιτίας Εξαίρεσης ΦΠΑ
     */
    public function getVatExemptionCategory(): ?int
    {
        return $this->get('vatExemptionCategory');
    }

    /**
     * <h2>Κατηγορία Αιτίας Εξαίρεσης ΦΠΑ</h2>
     *
     * <p>Το πεδίο αυτό είναι απαραίτητο στην περίπτωση που το
     * vatCategory υποδηλώνει κατηγορία συντελεστή 0% ΦΠΑ.</p>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $vatExemptionCategory Ελάχιστη τιμή = 1, Μέγιστη τιμή = 23
     */
    public function setVatExemptionCategory(int $vatExemptionCategory): self
    {
        return $this->put('vatExemptionCategory', $vatExemptionCategory);
    }

    /**
     * @return ShipType|null ΠΟΛ 1177/2018 Αρ. 27
     */
    public function getDienergia(): ?ShipType
    {
        return $this->get('dienergia');
    }

    /**
     * ΠΟΛ 1177/2018 Αρ. 27
     *
     * @param ShipType $dienergia ΠΟΛ 1177/2018 Αρ. 27
     */
    public function setDienergia(ShipType $dienergia): self
    {
        return $this->put('dienergia', $dienergia);
    }

    /**
     * @return bool|null Δικαίωμα Έκπτωσης
     */
    public function getDiscountOption(): ?bool
    {
        return $this->get('discountOption');
    }

    /**
     * <h2>Δικαίωμα Έκπτωσης</h2>
     *
     * @param bool $discountOption True / False
     */
    public function setDiscountOption(bool $discountOption): self
    {
        return $this->put('discountOption', $discountOption);
    }

    /**
     * @return float|null Ποσό Παρακράτησης Φόρου
     */
    public function getWithheldAmount(): ?float
    {
        return $this->get('withheldAmount');
    }

    /**
     * <h2>Ποσό Παρακράτησης Φόρου</h2>
     *
     * @param float $withheldAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setWithheldAmount(float $withheldAmount): self
    {
        return $this->put('withheldAmount', $withheldAmount);
    }

    /**
     * @return int|null Κατηγορία Συντελεστή Παρακράτησης Φόρου
     */
    public function getWithheldPercentCategory(): ?int
    {
        return $this->get('withheldPercentCategory');
    }

    /**
     * <h2>Κατηγορία Συντελεστή Παρακράτησης Φόρου</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $withheldPercentCategory Ελάχιστη τιμή = 1, Μέγιστη τιμή = 15
     */
    public function setWithheldPercentCategory(int $withheldPercentCategory): self
    {
        return $this->put('withheldPercentCategory', $withheldPercentCategory);
    }

    /**
     * @return float|null Ποσό Χαρτοσήμου
     */
    public function getStampDutyAmount(): ?float
    {
        return $this->get('stampDutyAmount');
    }

    /**
     * <h2>Ποσό Χαρτοσήμου</h2>
     *
     * @param float $stampDutyAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setStampDutyAmount(float $stampDutyAmount): self
    {
        return $this->put('stampDutyAmount', $stampDutyAmount);
    }

    /**
     * @return int|null Κατηγορία Συντελεστή Χαρτοσήμου
     */
    public function getStampDutyPercentCategory(): ?int
    {
        return $this->get('stampDutyPercentCategory');
    }

    /**
     * <h2>Κατηγορία Συντελεστή Χαρτοσήμου</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $stampDutyPercentCategory Λίστα τιμών: 1, 2, 3
     */
    public function setStampDutyPercentCategory(int $stampDutyPercentCategory): self
    {
        return $this->put('stampDutyPercentCategory', $stampDutyPercentCategory);
    }

    /**
     * @return float|null Ποσό Τελών
     */
    public function getFeesAmount(): ?float
    {
        return $this->get('feesAmount');
    }

    /**
     * <h2>Ποσό Τελών</h2>
     *
     * @param float $feesAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setFeesAmount(float $feesAmount): self
    {
        return $this->put('feesAmount', $feesAmount);
    }

    /**
     * @return int|null Κατηγορία Συντελεστή Τελών
     */
    public function getFeesPercentCategory(): ?int
    {
        return $this->get('feesPercentCategory');
    }

    /**
     * <h2>Κατηγορία Συντελεστή Τελών</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $feesPercentCategory Ελάχιστη τιμή = 1, Μέγιστη τιμή = 9
     */
    public function setFeesPercentCategory(int $feesPercentCategory): self
    {
        return $this->put('feesPercentCategory', $feesPercentCategory);
    }

    /**
     * @return int|null Κατηγορία Συντελεστή Λοιπών Φόρων
     */
    public function getOtherTaxesPercentCategory(): ?int
    {
        return $this->get('otherTaxesPercentCategory');
    }

    /**
     * <h2>Κατηγορία Συντελεστή Λοιπών Φόρων</h2>
     *
     * <p>Οι πιθανές τιμές περιγράφονται αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος.</p>
     *
     * @param int $otherTaxesPercentCategory Ελάχιστη τιμή = 1, Μέγιστη τιμή = 14
     */
    public function setOtherTaxesPercentCategory(int $otherTaxesPercentCategory): self
    {
        return $this->put('feesPercentCategory', $otherTaxesPercentCategory);
    }

    /**
     * @return float|null Ποσό Λοιπών Φόρων
     */
    public function getOtherTaxesAmount(): ?float
    {
        return $this->get('otherTaxesAmount');
    }

    /**
     * <h2>Ποσό Λοιπών Φόρων</h2>
     *
     * @param float $otherTaxesAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setOtherTaxesAmount(float $otherTaxesAmount): self
    {
        return $this->put('otherTaxesAmount', $otherTaxesAmount);
    }

    /**
     * @return float|null Ποσό Κρατήσεων
     */
    public function getDeductionsAmount(): ?float
    {
        return $this->get('deductionsAmount');
    }

    /**
     * <h2>Ποσό Κρατήσεων</h2>
     *
     * @param float $deductionsAmount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setDeductionsAmount(float $deductionsAmount): self
    {
        return $this->put('deductionsAmount', $deductionsAmount);
    }

    /**
     * @return string|null Σχόλια Γραμμής
     */
    public function getLineComments(): ?string
    {
        return $this->get('lineComments');
    }

    /**
     * <h2>Σχόλια Γραμμής</h2>
     *
     * <p>Τα σχόλια γραμμής συμπληρώνονται από τον χρήστη και χρησιμοποιούνται για
     * πληροφοριακούς λόγους προς την υπηρεσία.</p>
     *
     * @param string $lineComments Σχόλια Γραμμής
     */
    public function setLineComments(string $lineComments): self
    {
        return $this->put('lineComments', $lineComments);
    }

    /**
     * @return array|null Χαρακτηρισμοί Εσόδων
     */
    public function getIncomeClassifications(): ?array
    {
        return $this->get('incomeClassification');
    }

    /**
     * <h2>Χαρακτηρισμοί Εσόδων</h2>
     *
     * <p>Οι χαρακτηρισμοί που αφορούν τον υποβάλλοντα (εκδότης – εσόδων)
     * υποβάλλονται μαζί με το παραστατικό με την αντίστοιχη χρήση του
     * πεδίου incomeClassification.</p>
     *
     * @param IncomeClassificationType $incomeClassification Χαρακτηρισμοί Εσόδων
     */
    public function addIncomeClassification(IncomeClassificationType $incomeClassification): self
    {
        return $this->put('incomeClassification', $incomeClassification);
    }

    /**
     * @return array|null Χαρακτηρισμοί Εξόδων
     */
    public function getExpensesClassifications(): ?array
    {
        return $this->get('expensesClassification');
    }

    /**
     * <h2>Χαρακτηρισμοί Εξόδων</h2>
     *
     * <p>Οι χαρακτηρισμοί που αφορούν τον υποβάλλοντα (λήπτης εξόδων),
     * υποβάλλονται μαζί με το παραστατικό με την αντίστοιχη χρήση του
     * πεδίου expensesClassification.</p>
     *
     * @param ExpensesClassificationType $expensesClassification Χαρακτηρισμοί Εξόδων
     */
    public function addExpensesClassification(ExpensesClassificationType $expensesClassification): self
    {
        return $this->put('expensesClassification', $expensesClassification);
    }

    public function put($key, $value): self
    {
        if ($key === 'expensesClassification' || $key === 'incomeClassification') {
            $this->attributes[$key][] = $value;
            return $this;
        }
        
        return parent::put($key, $value);
    }
}