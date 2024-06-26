<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\FuelCode;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceDetailType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * <ul>
 *  <li>Τα πεδία otherMeasurementUnitQuantity και otherMeasurementUnitTitle
 *  συμπληρώνονται υποχρεωτικά στην περίπτωση που έχει επιλεγεί measurementUnit
 *  = 7 (Τεμάχια_Λοιπές Περιπτώσεις). Με την επιλογή measurementUnit = 7
 *  (Τεμάχια_Λοιπές Περιπτώσεις) ως μονάδα μέτρησης για τις περιπτώσεις
 *  παραστατικών διακίνησης, δίνεται η δυνατότητα να διαβιβαστεί η πληροφορία
 *  μονάδας μέτρησης που δε συμπεριλαμβάνεται σε κάποια από τις διαθέσιμες τιμές
 *  της λίστας, με αριθμητική απεικόνιση του πλήθους
 *  (otherMeasurementUnitQuantity) που αντιστοιχεί στο είδος συσκευασίας και
 *  σύντομη αναγραφή του είδους συσκευασίας στο λεκτικό πεδίο
 *  (otherMeasurementUnitTitle) π.χ. 3_Παλέτες. Σημειώνεται ότι το πεδίο quantity
 *  («Ποσότητα») σε κάθε περίπτωση αντιστοιχεί στο πλήθος των ειδών που
 *  διακινούνται και όχι στο πλήθος των ειδών συσκευασίας.</li>
 * </ul>
 */
class InvoiceDetails extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'lineNumber',
        'recType',
        'TaricNo',
        'itemCode',
        'itemDescr',
        'fuelCode',
        'quantity',
        'measurementUnit',
        'invoiceDetailType',
        'netValue',
        'vatCategory',
        'vatAmount',
        'vatExemptionCategory',
        'dienergia',
        'discountOption',
        'withheldAmount',
        'withheldPercentCategory',
        'stampDutyAmount',
        'stampDutyPercentCategory',
        'feesAmount',
        'feesPercentCategory',
        'otherTaxesPercentCategory',
        'otherTaxesAmount',
        'deductionsAmount',
        'lineComments',
        'incomeClassification',
        'expensesClassification',
        'quantity15',
        'otherMeasurementUnitQuantity',
        'otherMeasurementUnitTitle'
    ];

    protected array $casts = [
        'recType'                   => RecType::class,
        'fuelCode'                  => FuelCode::class,
        'vatCategory'               => VatCategory::class,
        'measurementUnit'           => UnitMeasurement::class,
        'invoiceDetailType'         => InvoiceDetailType::class,
        'vatExemptionCategory'      => VatExemption::class,
        'withheldPercentCategory'   => WithheldPercentCategory::class,
        'stampDutyPercentCategory'  => StampCategory::class,
        'feesPercentCategory'       => FeesPercentCategory::class,
        'otherTaxesPercentCategory' => OtherTaxesPercentCategory::class,
        'dienergia'                 => Ship::class,
        'incomeClassification'      => IncomeClassification::class,
        'expensesClassification'    => ExpensesClassification::class,
    ];

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
        $this->set('lineNumber', $lineNumber);
    }

    /**
     * @return RecType|null Είδος Γραμμής
     */
    public function getRecType(): ?RecType
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
     * @param RecType|int $recType Είδος Γραμμής
     */
    public function setRecType(RecType|int $recType): void
    {
        $this->set('recType', $recType);
    }

    /**
     * @return FuelCode|null Κωδικός Καυσίμου
     */
    public function getFuelCode(): ?FuelCode
    {
        return $this->get('fuelCode');
    }

    /**
     * Οι πιθανές τιμές για το πεδίο fuelCode (κωδικός καυσίμου) περιγράφονται
     * αναλυτικά στον αντίστοιχο πίνακα του Παραρτήματος. Επιτρέπεται η αποστολή του
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
        $this->set('fuelCode', $fuelCode);
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
        $this->set('quantity', $quantity);
    }

    /**
     * @return UnitMeasurement|null Είδος Ποσότητας
     */
    public function getMeasurementUnit(): ?UnitMeasurement
    {
        return $this->get('measurementUnit');
    }

    /**
     * @param UnitMeasurement|string $measurementUnit Είδος Ποσότητας
     */
    public function setMeasurementUnit(UnitMeasurement|string $measurementUnit): void
    {
        $this->set('measurementUnit', $measurementUnit);
    }

    /**
     * @return InvoiceDetailType|null Επισήμανση
     */
    public function getInvoiceDetailType(): ?InvoiceDetailType
    {
        return $this->get('invoiceDetailType');
    }

    /**
     * @param InvoiceDetailType|string $invoiceDetailType Επισήμανση
     */
    public function setInvoiceDetailType(InvoiceDetailType|string $invoiceDetailType): void
    {
        $this->set('invoiceDetailType', $invoiceDetailType);
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
        $this->set('netValue', $netValue);
    }

    /**
     * @return VatCategory|null Κατηγορία ΦΠΑ
     */
    public function getVatCategory(): ?VatCategory
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
        $this->set('vatCategory', $vatCategory);
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
        $this->set('vatAmount', $vatAmount);
    }

    /**
     * @return VatExemption|null Κατηγορία Αιτίας Εξαίρεσης ΦΠΑ
     */
    public function getVatExemptionCategory(): ?VatExemption
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
        $this->set('vatExemptionCategory', $vatExemptionCategory);
    }

    /**
     * @return Ship|null ΠΟΛ 1177/2018 Αρ. 27
     */
    public function getDienergia(): ?Ship
    {
        return $this->get('dienergia');
    }

    /**
     * @param Ship|string $dienergia ΠΟΛ 1177/2018 Αρ. 27
     * @param string|null $applicationDate
     * @param string|null $doy
     * @param string|null $shipId
     */
    public function setDienergia(Ship|string $dienergia, string $applicationDate = null, string $doy = null, string $shipId = null): void
    {
        if ($dienergia instanceof Ship) {
            $this->set('dienergia', $dienergia);
        } else {
            $ship = new Ship();
            $ship->setApplicationId($dienergia);
            $ship->setApplicationDate($applicationDate);
            $ship->setDoy($doy);
            $ship->setShipID($shipId);
            $this->setDienergia($ship);
        }
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
        $this->set('discountOption', $discountOption);
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
        $this->set('withheldAmount', $withheldAmount);
    }

    /**
     * @return WithheldPercentCategory|null Κατηγορία Συντελεστή Παρακράτησης Φόρου
     */
    public function getWithheldPercentCategory(): ?WithheldPercentCategory
    {
        return $this->get('withheldPercentCategory');
    }

    /**
     * @param WithheldPercentCategory|int $withheldPercentCategory Κατηγορία Συντελεστή Παρακράτησης Φόρου
     */
    public function setWithheldPercentCategory(WithheldPercentCategory|int $withheldPercentCategory): void
    {
        $this->set('withheldPercentCategory', $withheldPercentCategory);
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
        $this->set('stampDutyAmount', $stampDutyAmount);
    }

    /**
     * @return StampCategory|null Κατηγορία Συντελεστή Χαρτοσήμου
     */
    public function getStampDutyPercentCategory(): ?StampCategory
    {
        return $this->get('stampDutyPercentCategory');
    }

    /**
     * @param StampCategory|string $stampDutyPercentCategory Κατηγορία Συντελεστή Χαρτοσήμου
     */
    public function setStampDutyPercentCategory(StampCategory|string $stampDutyPercentCategory): void
    {
        $this->set('stampDutyPercentCategory', $stampDutyPercentCategory);
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
        $this->set('feesAmount', $feesAmount);
    }

    /**
     * @return FeesPercentCategory|null Κατηγορία Συντελεστή Τελών
     */
    public function getFeesPercentCategory(): ?FeesPercentCategory
    {
        return $this->get('feesPercentCategory');
    }

    /**
     * @param FeesPercentCategory|string $feesPercentCategory Κατηγορία Συντελεστή Τελών
     */
    public function setFeesPercentCategory(FeesPercentCategory|string $feesPercentCategory): void
    {
        $this->set('feesPercentCategory', $feesPercentCategory);
    }

    /**
     * @return OtherTaxesPercentCategory|null Κατηγορία Συντελεστή Λοιπών Φόρων
     */
    public function getOtherTaxesPercentCategory(): ?OtherTaxesPercentCategory
    {
        return $this->get('otherTaxesPercentCategory');
    }

    /**
     * @param OtherTaxesPercentCategory|string $otherTaxesPercentCategory Κατηγορία Συντελεστή Λοιπών Φόρων
     */
    public function setOtherTaxesPercentCategory(OtherTaxesPercentCategory|string $otherTaxesPercentCategory): void
    {
        $this->set('otherTaxesPercentCategory', $otherTaxesPercentCategory);
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
        $this->set('otherTaxesAmount', $otherTaxesAmount);
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
        $this->set('deductionsAmount', $deductionsAmount);
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
        $this->set('lineComments', $lineComments);
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
        $this->set('incomeClassification', $incomeClassification);
    }

    /**
     * Προσθήκη χαρακτηρισμού εσόδων.
     *
     * @param IncomeClassification|IncomeClassificationType|null $incomeClassification Χαρακτηρισμός Εσόδων
     * @param IncomeClassificationCategory|null $classificationCategory
     * @param float|null $classificationAmount
     */
    public function addIncomeClassification(IncomeClassification|IncomeClassificationType|null $incomeClassification, IncomeClassificationCategory $classificationCategory = null, float $classificationAmount = null): void
    {
        if ($incomeClassification instanceof IncomeClassification) {
            $this->push('incomeClassification', $incomeClassification);
        } else {
            $classification = new IncomeClassification();
            $classification->setClassificationType($incomeClassification);
            $classification->setClassificationCategory($classificationCategory);
            $classification->setAmount($classificationAmount);
            $this->addIncomeClassification($classification);
        }
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
        $this->set('expensesClassification', $expensesClassification);
    }

    /**
     * Προσθήκη χαρακτηρισμού εξόδων.
     *
     * @param ExpensesClassification|ExpenseClassificationType|null $expenseClassification Χαρακτηρισμός εξόδων
     * @param ExpenseClassificationCategory|null $expenseClassificationCategory
     * @param float|null $classificationAmount
     */
    public function addExpensesClassification(ExpensesClassification|ExpenseClassificationType|null $expenseClassification, ExpenseClassificationCategory $expenseClassificationCategory = null, float $classificationAmount = null): void
    {
        if ($expenseClassification instanceof ExpensesClassification) {
            $this->push('expensesClassification', $expenseClassification);
        } else {
            $classification = new ExpensesClassification();
            $classification->setClassificationType($expenseClassification);
            $classification->setClassificationCategory($expenseClassificationCategory);
            $classification->setAmount($classificationAmount);
            $this->addExpensesClassification($classification);
        }
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
        $this->set('quantity15', $quantity15);
    }

    /**
     * @return string|null Περιγραφή Είδους
     * @version 1.0.8
     */
    public function getItemDescr(): ?string
    {
        return $this->get('itemDescr');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση παραστατικών της ειδικής κατηγορίας
     * tax free ή που είναι τιμολόγια και δελτία αποστολής ή απλά
     * δελτία διακίνησης (π.χ 9.3).
     *
     * @param string|null $itemDescr Περιγραφή Είδους (Μέγιστο επιτρεπτό μήκος 300)
     * @version 1.0.8
     */
    public function setItemDescr(?string $itemDescr): void
    {
        $this->set('itemDescr', $itemDescr);
    }

    /**
     * @return mixed|null Κωδικός Taric
     * @version 1.0.8
     */
    public function getTaricNo(): ?string
    {
        return $this->get('TaricNo');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση παραστατικών που είναι τιμολόγια και
     * δελτία αποστολής ή απλά δελτία διακίνησης (π.χ 9.3).
     *
     * @param string|null $taricNo Κωδικός Taric (Υποχρεωτικό μήκος 10)
     * @version 1.0.8
     */
    public function setTaricNo(?string $taricNo): void
    {
        $this->set('TaricNo', $taricNo);
    }

    /**
     * @return string|null Κωδικός Είδους
     * @version 1.0.8
     */
    public function getItemCode(): ?string
    {
        return $this->get('itemCode');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση παραστατικών που είναι τιμολόγια και
     * δελτία αποστολής ή απλά δελτία διακίνησης (π.χ 9.3).
     *
     * @param string|null $itemCode Κωδικός Είδους (Μέγιστο επιτρεπτό μήκος 10)
     * @version 1.0.8
     */
    public function setItemCode(?string $itemCode): void
    {
        $this->set('itemCode', $itemCode);
    }

    /**
     * @return int|null Πλήθος Μονάδας Μέτρησης Τεμάχια Άλλα
     * @version 1.0.8
     */
    public function getOtherMeasurementUnitQuantity(): ?int
    {
        return $this->get('otherMeasurementUnitQuantity');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση που measurementUnit = 7
     * (Τεμάχια_Λοιπές Περιπτώσεις).
     *
     * @param int|null $otherMeasurementUnitQuantity Πλήθος Μονάδας Μέτρησης Τεμάχια Άλλα
     * @version 1.0.8
     */
    public function setOtherMeasurementUnitQuantity(?int $otherMeasurementUnitQuantity): void
    {
        $this->set('otherMeasurementUnitQuantity', $otherMeasurementUnitQuantity);
    }

    /**
     * @return string|null Τίτλος Μονάδας Μέτρησης Τεμάχια Άλλα
     * @version 1.0.8
     */
    public function getOtherMeasurementUnitTitle(): ?string
    {
        return $this->get('otherMeasurementUnitTitle');
    }

    /**
     * Αποδεκτό μόνο στην περίπτωση που measurementUnit = 7
     * (Τεμάχια_Λοιπές Περιπτώσεις).
     *
     * @param string|null $otherMeasurementUnitTitle Τίτλος Μονάδας Μέτρησης Τεμάχια Άλλα
     * @version 1.0.8
     */
    public function setOtherMeasurementUnitTitle(?string $otherMeasurementUnitTitle): void
    {
        $this->set('otherMeasurementUnitTitle', $otherMeasurementUnitTitle);
    }

    public function set($key, $value): void
    {
        if ($key === 'expensesClassification' || $key === 'incomeClassification') {
            if (is_array($value)) {
                parent::set($key, $value);
            } else {
                $this->push($key, $value);
            }
            return;
        }

        parent::set($key, $value);
    }
}