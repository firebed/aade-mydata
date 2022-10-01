<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\FeeType;
use Firebed\AadeMyData\Enums\FuelCode;
use Firebed\AadeMyData\Enums\InvoiceDetailType;
use Firebed\AadeMyData\Enums\OtherTaxesCategory;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;

class InvoiceDetails extends Type
{
    /**
     * @return int|null ΑΑ γραμμής
     */
    public function getLineNumber(): ?int
    {
        return $this->get('lineNumber');
    }

    /**
     * ΑΑ γραμμής.
     *
     * @param int $lineNumber Ελάχιστη τιμή = 1
     */
    public function setLineNumber(int $lineNumber): void
    {
        $this->put('lineNumber', $lineNumber);
    }

    /**
     * @return int|null Είδος Γραμμής
     */
    public function getRecType(): ?int
    {
        return $this->get('recType');
    }

    /**
     * <ul>
     * <li>Στην περίπτωση αποστολής γραμμών με recType = 2 (γραμμή τέλους με ΦΠΑ)
     * ή/και recType = 3 (Γραμμή Λοιπών Φόρων με Φ.Π.Α.), θα επιτρέπεται παράλληλα,
     * εφόσον είναι επιθυμητό, η αποστολή παρακρατούμενων / τελών / λοιπών φόρων /
     * χαρτοσήμου / κρατήσεων και σε επίπεδο παραστατικού και όχι υποχρεωτικά ανά
     * γραμμή σύνοψης παραστατικού.</li>
     *
     * <li>Στις περιπτώσεις αυτών των γραμμών, τα ποσά που αντιστοιχούν στα τέλη με ΦΠΑ
     * (recType = 2) ή τους λοιπούς φόρους (recType = 3) αντίστοιχα, θα αποστέλλονται
     * στο πεδίο της καθαρής αξίας της γραμμής (netValue), ενώ τα αντίστοιχα πεδία
     * ποσό τέλους (feesAmount) ή ποσό λοιπών φόρων (otherTaxesAmount) δε θα συμπληρώνονται.</li
     * Επίσης στις γραμμές αυτές δεν επιτρέπεται η αποστολή άλλων ειδών
     * φόρων/τελών/κρατήσεων/χαρτοσήμου (π.χ. σε μια γραμμή με recType = 2 δεν επιτρέπονται
     * στη γραμμή αυτή η αποστολή λοιπών φόρων/κρατήσεων/παρακρατούμενων/χαρτοσήμου).</li>
     *
     * <li>Η αποστολή με recType = 7 (αρνητικό πρόσημο αξιών) επιτρέπεται μόνο στην
     * περίπτωση διαβίβασης παραστατικών 17.3, 17.4, 17.5 και 17.6 και με αυτόν τον
     * τρόπο υποδηλώνεται ότι οι αξίες της γραμμής είναι αρνητικές (στα αντίστοιχα
     * πεδία των αξιών οι τιμές αναγράφονται στις απόλυτες/θετικές τιμές τους).
     * Σημειώνεται ότι στα αθροίσματα των αξιών στην ενότητα Περίληψη Παραστατικού
     * (InvoiceSummaryType) θα διαβιβάζονται τα αθροίσματα των απόλυτων τιμών των
     * αντίστοιχων αξιών των γραμμών ανεξάρτητα αν υπάρχουν γραμμές που φέρουν ή
     * όχι την ένδειξη recType = 7.</li>
     * </ul>
     *
     * @param int $recType Είδος Γραμμής
     */
    public function setRecType(int $recType): void
    {
        $this->put('recType', $recType);
    }

    /**
     * @return string|null Κωδικός Καυσίμου
     */
    public function getFuelCode(): ?string
    {
        return $this->get('fuelCode');
    }

    /**
     * Οι πιθανές τιμές για το πεδίο fuelCode (κωδικός καυσίμου) περιγράφονται
     * αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος. Eπιτρέπεται η αποστολή του
     * μόνο για την περίπτωση των παρόχων και εφόσον πρόκειται για παραστατικό
     * καυσίμων (invoiceHeaderType.fuelInvoice = true). Η τιμή 999 χρησιμοποιείται στην
     * περίπτωση που σε ένα παραστατικό εκτός από καύσιμα υπάρχει η ανάγκη
     * τιμολόγησης και λοιπών χρεώσεων. Επιτρέπεται ανά παραστατικό μόνο μια γραμμή
     * με αυτόν τον κωδικό και η καθαρή αξία αυτής της γραμμής θα πρέπει να είναι
     * μικρότερη ή ίση από το άθροισμα της καθαρής αξίας των υπόλοιπων κωδικών
     * καυσίμου του παραστατικού.
     *
     * @param FuelCode|string $fuelCode Κωδικός Καυσίμου
     */
    public function setFuelCode(FuelCode|string $fuelCode): void
    {
        $this->put('fuelCode', $fuelCode);
    }

    /**
     * @return float|null Ποσότητα
     */
    public function getQuantity(): ?float
    {
        return $this->get('quantity');
    }

    /**
     * @param float $quantity Ποσότητα
     */
    public function setQuantity(float $quantity): void
    {
        $this->put('quantity', $quantity);
    }

    /**
     * @return string|null Είδος Ποσότητας
     */
    public function getMeasurementUnit(): ?string
    {
        return $this->get('measurementUnit');
    }

    /**
     * @param UnitMeasurement|string $measurementUnit Είδος Ποσότητας
     */
    public function setMeasurementUnit(UnitMeasurement|string $measurementUnit): void
    {
        $this->put('measurementUnit', $measurementUnit);
    }

    /**
     * @return string|null Επισήμανση
     */
    public function getInvoiceDetailType(): ?string
    {
        return $this->get('invoiceDetailType');
    }

    /**
     * @param InvoiceDetailType|string $invoiceDetailType Επισήμανση
     */
    public function setInvoiceDetailType(InvoiceDetailType|string $invoiceDetailType): void
    {
        $this->put('invoiceDetailType', $invoiceDetailType);
    }

    /**
     * @return float|null Καθαρή αξία
     */
    public function getNetValue(): ?float
    {
        return $this->get('netValue');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $netValue Καθαρή αξία
     */
    public function setNetValue(float $netValue): void
    {
        $this->put('netValue', $netValue);
    }

    /**
     * @return string|null Κατηγορία ΦΠΑ
     */
    public function getVatCategory(): ?string
    {
        return $this->get('vatCategory');
    }

    /**
     * Για περιπτώσεις λογιστικών εγγραφών όπου δεν εφαρμόζεται ΦΠΑ,
     * το πεδίο vatCategory θα έχει την τιμή 8.
     *
     * @param VatCategory|string $vatCategory Κατηγορία ΦΠΑ
     */
    public function setVatCategory(VatCategory|string $vatCategory): void
    {
        $this->put('vatCategory', $vatCategory);
    }

    /**
     * @return float|null Ποσό ΦΠΑ
     */
    public function getVatAmount(): ?float
    {
        return $this->get('vatAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $vatAmount Ποσό ΦΠΑ
     */
    public function setVatAmount(float $vatAmount): void
    {
        $this->put('vatAmount', $vatAmount);
    }

    /**
     * @return string|null Κατηγορία Αιτίας Εξαίρεσης ΦΠΑ
     */
    public function getVatExemptionCategory(): ?string
    {
        return $this->get('vatExemptionCategory');
    }

    /**
     * Είναι απαραίτητο στην περίπτωση που το vatCategory υποδηλώνει
     * κατηγορία συντελεστή 0% ΦΠΑ.
     *
     * @param VatExemption|string $vatExemptionCategory Κατηγορία Αιτίας Εξαίρεσης ΦΠΑ
     */
    public function setVatExemptionCategory(VatExemption|string $vatExemptionCategory): void
    {
        $this->put('vatExemptionCategory', $vatExemptionCategory);
    }

    /**
     * @return Ship|null ΠΟΛ 1177/2018 Αρ. 27
     */
    public function getDienergia(): ?Ship
    {
        return $this->get('dienergia');
    }

    /**
     * @param Ship $dienergia ΠΟΛ 1177/2018 Αρ. 27
     */
    public function setDienergia(Ship $dienergia): void
    {
        $this->put('dienergia', $dienergia);
    }

    /**
     * @return bool|null Δικαίωμα Έκπτωσης
     */
    public function isDiscountOption(): ?bool
    {
        return $this->get('discountOption');
    }

    /**
     * @param bool $discountOption Δικαίωμα Έκπτωσης
     */
    public function setDiscountOption(bool $discountOption): void
    {
        $this->put('discountOption', $discountOption);
    }

    /**
     * @return float|null Ποσό Παρακράτησης Φόρου
     */
    public function getWithheldAmount(): ?float
    {
        return $this->get('withheldAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $withheldAmount Ποσό Παρακράτησης Φόρου
     */
    public function setWithheldAmount(float $withheldAmount): void
    {
        $this->put('withheldAmount', $withheldAmount);
    }

    /**
     * @return string|null Κατηγορία Συντελεστή Παρακράτησης Φόρου
     */
    public function getWithheldPercentCategory(): ?string
    {
        return $this->get('withheldPercentCategory');
    }

    /**
     * @param WithheldPercentCategory|string $withheldPercentCategory Κατηγορία Συντελεστή Παρακράτησης Φόρου
     */
    public function setWithheldPercentCategory(WithheldPercentCategory|string $withheldPercentCategory): void
    {
        $this->put('withheldPercentCategory', $withheldPercentCategory);
    }

    /**
     * @return float|null Ποσό Χαρτοσήμου
     */
    public function getStampDutyAmount(): ?float
    {
        return $this->get('stampDutyAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $stampDutyAmount Ποσό Χαρτοσήμου
     */
    public function setStampDutyAmount(float $stampDutyAmount): void
    {
        $this->put('stampDutyAmount', $stampDutyAmount);
    }

    /**
     * @return string|null Κατηγορία Συντελεστή Χαρτοσήμου
     */
    public function getStampDutyPercentCategory(): ?string
    {
        return $this->get('stampDutyPercentCategory');
    }

    /**
     * @param StampCategory|string $stampDutyPercentCategory Κατηγορία Συντελεστή Χαρτοσήμου
     */
    public function setStampDutyPercentCategory(StampCategory|string $stampDutyPercentCategory): void
    {
        $this->put('stampDutyPercentCategory', $stampDutyPercentCategory);
    }

    /**
     * @return float|null Ποσό Τελών
     */
    public function getFeesAmount(): ?float
    {
        return $this->get('feesAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $feesAmount Ποσό Τελών
     */
    public function setFeesAmount(float $feesAmount): void
    {
        $this->put('feesAmount', $feesAmount);
    }

    /**
     * @return int|null Κατηγορία Συντελεστή Τελών
     */
    public function getFeesPercentCategory(): ?int
    {
        return $this->get('feesPercentCategory');
    }

    /**
     * @param FeeType|string $feesPercentCategory Κατηγορία Συντελεστή Τελών
     */
    public function setFeesPercentCategory(FeeType|string $feesPercentCategory): void
    {
        $this->put('feesPercentCategory', $feesPercentCategory);
    }

    /**
     * @return string|null Κατηγορία Συντελεστή Λοιπών Φόρων
     */
    public function getOtherTaxesPercentCategory(): ?string
    {
        return $this->get('otherTaxesPercentCategory');
    }

    /**
     * @param OtherTaxesCategory|string $otherTaxesPercentCategory Κατηγορία Συντελεστή Λοιπών Φόρων
     */
    public function setOtherTaxesPercentCategory(OtherTaxesCategory|string $otherTaxesPercentCategory): void
    {
        $this->put('otherTaxesPercentCategory', $otherTaxesPercentCategory);
    }

    /**
     * @return float|null Ποσό Λοιπών Φόρων
     */
    public function getOtherTaxesAmount(): ?float
    {
        return $this->get('otherTaxesAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $otherTaxesAmount Ποσό Λοιπών Φόρων
     */
    public function setOtherTaxesAmount(float $otherTaxesAmount): void
    {
        $this->put('otherTaxesAmount', $otherTaxesAmount);
    }

    /**
     * @return float|null Ποσό Κρατήσεων
     */
    public function getDeductionsAmount(): ?float
    {
        return $this->get('deductionsAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float $deductionsAmount Ποσό Κρατήσεων
     */
    public function setDeductionsAmount(float $deductionsAmount): void
    {
        $this->put('deductionsAmount', $deductionsAmount);
    }

    /**
     * @return string|null Σχόλια Γραμμής
     */
    public function getLineComments(): ?string
    {
        return $this->get('lineComments');
    }

    /**
     * Συμπληρώνονται από τον χρήστη και χρησιμοποιούνται για πληροφοριακούς
     * λόγους προς την υπηρεσία.
     *
     * @param string $lineComments Σχόλια Γραμμής
     */
    public function setLineComments(string $lineComments): void
    {
        $this->put('lineComments', $lineComments);
    }

    /**
     * @return IncomeClassification[]|null Χαρακτηρισμοί Εσόδων
     */
    public function getIncomeClassification(): ?array
    {
        return $this->get('incomeClassification');
    }

    /**
     * Αφορούν τον υποβάλλοντα (εκδότης – εσόδων) υποβάλλονται μαζί με το
     * παραστατικό με την αντίστοιχη χρήση του πεδίου incomeClassification.
     *
     * @param IncomeClassification[] $incomeClassification Χαρακτηρισμοί Εσόδων
     */
    public function setIncomeClassification(array $incomeClassification): void
    {
        $this->put('incomeClassification', $incomeClassification);
    }

    /**
     * Προσθήκη χαρακτηρισμού εσόδων.
     *
     * @param IncomeClassification $incomeClassification Χαρακτηρισμός Εσόδων
     */
    public function addIncomeClassification(IncomeClassification $incomeClassification): void
    {
        $this->push('incomeClassification', $incomeClassification);
    }

    /**
     * @return ExpensesClassification[]|null Χαρακτηρισμοί Εξόδων
     */
    public function getExpensesClassification(): ?array
    {
        return $this->get('expensesClassification');
    }

    /**
     * Οι χαρακτηρισμοί που αφορούν τον υποβάλλοντα (λήπτης εξόδων),
     * υποβάλλονται μαζί με το παραστατικό με την αντίστοιχη χρήση του
     * πεδίου expensesClassification.
     *
     * @param ExpensesClassification[] $expensesClassification Χαρακτηρισμοί Εξόδων
     */
    public function setExpensesClassification(array $expensesClassification): void
    {
        $this->put('expensesClassification', $expensesClassification);
    }

    /**
     * Προσθήκη χαρακτηρισμού εξόδων.
     *
     * @param ExpensesClassification $expensesClassification Χαρακτηρισμός εξόδων
     */
    public function addExpensesClassification(ExpensesClassification $expensesClassification): void
    {
        $this->push('expensesClassification', $expensesClassification);
    }

    /**
     * @return float|null Ποσότητα Θερμοκρασίας 15 βαθμών
     */
    public function getQuantity15(): ?float
    {
        return $this->get('quantity15');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση αποστολής από παρόχους και για την περίπτωση που το
     * παραστατικό είναι παραστατικό καυσίμων.
     *
     * @param float|null $quantity15 Ελάχιστη τιμή = 0
     */
    public function setQuantity15(?float $quantity15): void
    {
        $this->put('quantity15', $quantity15);
    }

    /**
     * @return string|null Περιγραφή Είδους
     */
    public function getItemDescr(): ?string
    {
        return $this->get('itemDescr');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση παραστατικών της ειδικής κατηγορίας tax free.
     *
     * @param string|null $itemDescr Μέγιστο επιτρεπτό μήκος 300
     */
    public function setItemDescr(?string $itemDescr): void
    {
        $this->put('itemDescr', $itemDescr);
    }

    public function put($key, $value): void
    {
        if ($key === 'expensesClassification' || $key === 'incomeClassification') {
            $this->push($key, $value);
            return;
        }

        parent::put($key, $value);
    }
}