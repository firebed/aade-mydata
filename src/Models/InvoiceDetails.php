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
use InvalidArgumentException;

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
        'otherMeasurementUnitTitle',
        'notVAT195',
    ];

    protected array $casts = [
        'recType' => RecType::class,
        'fuelCode' => FuelCode::class,
        'vatCategory' => VatCategory::class,
        'measurementUnit' => UnitMeasurement::class,
        'invoiceDetailType' => InvoiceDetailType::class,
        'vatExemptionCategory' => VatExemption::class,
        'withheldPercentCategory' => WithheldPercentCategory::class,
        'stampDutyPercentCategory' => StampCategory::class,
        'feesPercentCategory' => FeesPercentCategory::class,
        'otherTaxesPercentCategory' => OtherTaxesPercentCategory::class,
        'dienergia' => Ship::class,
        'incomeClassification' => IncomeClassification::class,
        'expensesClassification' => ExpensesClassification::class,
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
     * @param  int  $lineNumber  Ελάχιστη τιμή = 1
     */
    public function setLineNumber(int $lineNumber): static
    {
        return $this->set('lineNumber', $lineNumber);
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
     * @param  RecType|int|null  $recType  Είδος Γραμμής
     * @return InvoiceDetails
     */
    public function setRecType(RecType|int|null $recType): static
    {
        return $this->set('recType', $recType);
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
     * @param  FuelCode|string|null  $fuelCode  Κωδικός Καυσίμου
     * @return InvoiceDetails
     */
    public function setFuelCode(FuelCode|string|null $fuelCode): static
    {
        return $this->set('fuelCode', $fuelCode);
    }

    /**
     * @return float|null Ποσότητα
     */
    public function getQuantity(): ?float
    {
        return $this->get('quantity');
    }

    /**
     * @param  float|null  $quantity  Ποσότητα
     * @return InvoiceDetails
     */
    public function setQuantity(?float $quantity): static
    {
        return $this->set('quantity', $quantity);
    }

    /**
     * @return UnitMeasurement|null Είδος Ποσότητας
     */
    public function getMeasurementUnit(): ?UnitMeasurement
    {
        return $this->get('measurementUnit');
    }

    /**
     * @param  UnitMeasurement|string|null  $measurementUnit  Είδος Ποσότητας
     * @return InvoiceDetails
     */
    public function setMeasurementUnit(UnitMeasurement|string|null $measurementUnit): static
    {
        return $this->set('measurementUnit', $measurementUnit);
    }

    /**
     * @return InvoiceDetailType|null Επισήμανση
     */
    public function getInvoiceDetailType(): ?InvoiceDetailType
    {
        return $this->get('invoiceDetailType');
    }

    /**
     * @param  InvoiceDetailType|string|null  $invoiceDetailType  Επισήμανση
     * @return InvoiceDetails
     */
    public function setInvoiceDetailType(InvoiceDetailType|string|null $invoiceDetailType): static
    {
        return $this->set('invoiceDetailType', $invoiceDetailType);
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
     * @param  float  $netValue  Καθαρή αξία
     */
    public function setNetValue(float $netValue): static
    {
        return $this->set('netValue', $netValue);
    }

    public function addNetValue(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('netValue', $this->getNetValue() + $amount);
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
     * @param  VatCategory|string  $vatCategory  Κατηγορία ΦΠΑ
     */
    public function setVatCategory(VatCategory|string $vatCategory): static
    {
        return $this->set('vatCategory', $vatCategory);
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
     * @param  float  $vatAmount  Ποσό ΦΠΑ
     */
    public function setVatAmount(float $vatAmount): static
    {
        return $this->set('vatAmount', $vatAmount);
    }

    public function addVatAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('vatAmount', $this->getVatAmount() + $amount);
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
     * @param  VatExemption|string|null  $vatExemptionCategory  Κατηγορία Αιτίας Εξαίρεσης ΦΠΑ
     * @return InvoiceDetails
     */
    public function setVatExemptionCategory(VatExemption|string|null $vatExemptionCategory): static
    {
        return $this->set('vatExemptionCategory', $vatExemptionCategory);
    }

    /**
     * @return Ship|null ΠΟΛ 1177/2018 Αρ. 27
     */
    public function getDienergia(): ?Ship
    {
        return $this->get('dienergia');
    }

    /**
     * @param  Ship|string|null  $dienergia  ΠΟΛ 1177/2018 Αρ. 27
     * @param  string|null  $applicationDate
     * @param  string|null  $doy
     * @param  string|null  $shipId
     * @return InvoiceDetails
     */
    public function setDienergia(Ship|string|null $dienergia, string $applicationDate = null, string $doy = null, string $shipId = null): static
    {
        if ($dienergia instanceof Ship) {
            return $this->set('dienergia', $dienergia);
        } else {
            $ship = new Ship();
            $ship->setApplicationId($dienergia);
            $ship->setApplicationDate($applicationDate);
            $ship->setDoy($doy);
            $ship->setShipId($shipId);
            return $this->setDienergia($ship);
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
     * @param  bool|null  $discountOption  Δικαίωμα Έκπτωσης
     */
    public function setDiscountOption(?bool $discountOption): static
    {
        return $this->set('discountOption', $discountOption);
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
     * @param  float|null  $withheldAmount  Ποσό Παρακράτησης Φόρου
     * @return InvoiceDetails
     */
    public function setWithheldAmount(?float $withheldAmount): static
    {
        return $this->set('withheldAmount', $withheldAmount);
    }

    public function addWithheldAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('withheldAmount', $this->getWithheldAmount() + $amount);
    }

    /**
     * @return WithheldPercentCategory|null Κατηγορία Συντελεστή Παρακράτησης Φόρου
     */
    public function getWithheldPercentCategory(): ?WithheldPercentCategory
    {
        return $this->get('withheldPercentCategory');
    }

    /**
     * @param  WithheldPercentCategory|int|null  $withheldPercentCategory  Κατηγορία Συντελεστή Παρακράτησης Φόρου
     * @return InvoiceDetails
     */
    public function setWithheldPercentCategory(WithheldPercentCategory|int|null $withheldPercentCategory): static
    {
        return $this->set('withheldPercentCategory', $withheldPercentCategory);
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
     * @param  float|null  $stampDutyAmount  Ποσό Χαρτοσήμου
     * @return InvoiceDetails
     */
    public function setStampDutyAmount(?float $stampDutyAmount): static
    {
        return $this->set('stampDutyAmount', $stampDutyAmount);
    }

    public function addStampDutyAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('stampDutyAmount', $this->getStampDutyAmount() + $amount);
    }

    /**
     * @return StampCategory|null Κατηγορία Συντελεστή Χαρτοσήμου
     */
    public function getStampDutyPercentCategory(): ?StampCategory
    {
        return $this->get('stampDutyPercentCategory');
    }

    /**
     * @param  StampCategory|string|null  $stampDutyPercentCategory  Κατηγορία Συντελεστή Χαρτοσήμου
     * @return InvoiceDetails
     */
    public function setStampDutyPercentCategory(StampCategory|string|null $stampDutyPercentCategory): static
    {
        return $this->set('stampDutyPercentCategory', $stampDutyPercentCategory);
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
     * @param  float|null  $feesAmount  Ποσό Τελών
     * @return InvoiceDetails
     */
    public function setFeesAmount(float|null $feesAmount): static
    {
        return $this->set('feesAmount', $feesAmount);
    }

    public function addFeesAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('feesAmount', $this->getFeesAmount() + $amount);
    }

    /**
     * @return FeesPercentCategory|null Κατηγορία Συντελεστή Τελών
     */
    public function getFeesPercentCategory(): ?FeesPercentCategory
    {
        return $this->get('feesPercentCategory');
    }

    /**
     * @param  FeesPercentCategory|string|null  $feesPercentCategory  Κατηγορία Συντελεστή Τελών
     * @return InvoiceDetails
     */
    public function setFeesPercentCategory(FeesPercentCategory|string|null $feesPercentCategory): static
    {
        return $this->set('feesPercentCategory', $feesPercentCategory);
    }

    /**
     * @return OtherTaxesPercentCategory|null Κατηγορία Συντελεστή Λοιπών Φόρων
     */
    public function getOtherTaxesPercentCategory(): ?OtherTaxesPercentCategory
    {
        return $this->get('otherTaxesPercentCategory');
    }

    /**
     * @param  OtherTaxesPercentCategory|string|null  $otherTaxesPercentCategory  Κατηγορία Συντελεστή Λοιπών Φόρων
     * @return InvoiceDetails
     */
    public function setOtherTaxesPercentCategory(OtherTaxesPercentCategory|string|null $otherTaxesPercentCategory): static
    {
        return $this->set('otherTaxesPercentCategory', $otherTaxesPercentCategory);
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
     * @param  float|null  $otherTaxesAmount  Ποσό Λοιπών Φόρων
     * @return InvoiceDetails
     */
    public function setOtherTaxesAmount(?float $otherTaxesAmount): static
    {
        return $this->set('otherTaxesAmount', $otherTaxesAmount);
    }

    public function addOtherTaxesAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('otherTaxesAmount', $this->getOtherTaxesAmount() + $amount);
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
     * @param  float|null  $deductionsAmount  Ποσό Κρατήσεων
     * @return InvoiceDetails
     */
    public function setDeductionsAmount(?float $deductionsAmount): static
    {
        return $this->set('deductionsAmount', $deductionsAmount);
    }

    public function addDeductionsAmount(?float $amount): static
    {
        if ($amount === null) {
            return $this;
        }

        return $this->set('deductionsAmount', $this->getDeductionsAmount() + $amount);
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
     * @param  string|null  $lineComments  Σχόλια Γραμμής
     * @return InvoiceDetails
     */
    public function setLineComments(?string $lineComments): static
    {
        return $this->set('lineComments', $lineComments);
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
     * @param  IncomeClassification[]|null  $incomeClassification  Χαρακτηρισμοί Εσόδων
     */
    public function setIncomeClassification(?array $incomeClassification): static
    {
        return $this->set('incomeClassification', $incomeClassification);
    }

    /**
     * Προσθήκη χαρακτηρισμού εσόδων.
     *
     * @param  IncomeClassification|IncomeClassificationType|string|null  $type  Χαρακτηρισμός Εσόδων
     * @param  IncomeClassificationCategory|string|null  $category
     * @param  float|null  $amount
     * @return InvoiceDetails
     */
    public function addIncomeClassification(IncomeClassification|IncomeClassificationType|string|null $type, IncomeClassificationCategory|string $category = null, float $amount = null): static
    {
        if ($type instanceof IncomeClassification) {
            $this->push('incomeClassification', $type);
        } else {
            $classification = new IncomeClassification();
            $classification->setClassificationType($type);
            $classification->setClassificationCategory($category);
            $classification->setAmount($amount);
            $this->addIncomeClassification($classification);
        }

        return $this;
    }

    public function getTotalIncomeClassificationAmount(): float
    {
        if (empty($this->getIncomeClassification())) {
            return 0;
        }
        
        return round(array_reduce($this->getIncomeClassification(), function ($carry, IncomeClassification $classification) {
            return $carry + $classification->getAmount();
        }, 0), 2);
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
     * @param  ExpensesClassification[]|null  $expensesClassification  Χαρακτηρισμοί Εξόδων
     */
    public function setExpensesClassification(?array $expensesClassification): static
    {
        return $this->set('expensesClassification', $expensesClassification);
    }

    /**
     * Προσθήκη χαρακτηρισμού εξόδων.
     *
     * @param  ExpensesClassification|ExpenseClassificationType|string|null  $type  Χαρακτηρισμός εξόδων
     * @param  ExpenseClassificationCategory|string|null  $category
     * @param  float|null  $amount
     * @return InvoiceDetails
     */
    public function addExpensesClassification(ExpensesClassification|ExpenseClassificationType|string|null $type, ExpenseClassificationCategory|string $category = null, float $amount = null): static
    {
        if ($type instanceof ExpensesClassification) {
            $this->push('expensesClassification', $type);
        } else {
            $classification = new ExpensesClassification();
            $classification->setClassificationType($type);
            $classification->setClassificationCategory($category);
            $classification->setAmount($amount);
            $this->addExpensesClassification($classification);
        }

        return $this;
    }
    
    public function getTotalExpensesClassificationAmount(): float
    {
        if (empty($this->getExpensesClassification())) {
            return 0;
        }
        
        return round(array_reduce($this->getExpensesClassification(), function ($carry, ExpensesClassification $classification) {
            return $carry + $classification->getAmount();
        }, 0), 2);
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
     * @param  float|null  $quantity15  Ελάχιστη τιμή = 0
     */
    public function setQuantity15(?float $quantity15): static
    {
        return $this->set('quantity15', $quantity15);
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
     * @param  string|null  $description  Περιγραφή Είδους (Μέγιστο επιτρεπτό μήκος 300)
     * @version 1.0.8
     */
    public function setItemDescr(?string $description): static
    {
        return $this->set('itemDescr', $description);
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
     * @param  string|null  $taricNo  Κωδικός Taric (Υποχρεωτικό μήκος 10)
     * @version 1.0.8
     */
    public function setTaricNo(?string $taricNo): static
    {
        return $this->set('TaricNo', $taricNo);
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
     * @param  string|null  $itemCode  Κωδικός Είδους (Μέγιστο επιτρεπτό μήκος 10)
     * @version 1.0.8
     */
    public function setItemCode(?string $itemCode): static
    {
        return $this->set('itemCode', $itemCode);
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
     * @param  int|null  $otherMeasurementUnitQuantity  Πλήθος Μονάδας Μέτρησης Τεμάχια Άλλα
     * @version 1.0.8
     */
    public function setOtherMeasurementUnitQuantity(?int $otherMeasurementUnitQuantity): static
    {
        return $this->set('otherMeasurementUnitQuantity', $otherMeasurementUnitQuantity);
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
     * @param  string|null  $otherMeasurementUnitTitle  Τίτλος Μονάδας Μέτρησης Τεμάχια Άλλα
     * @version 1.0.8
     */
    public function setOtherMeasurementUnitTitle(?string $otherMeasurementUnitTitle): static
    {
        return $this->set('otherMeasurementUnitTitle', $otherMeasurementUnitTitle);
    }

    /**
     * @return bool|null Ένδειξη μη συμμετοχής στο ΦΠΑ (έσοδα – εκροές)
     * @version 1.0.9
     */
    public function getNotVAT195(): ?bool
    {
        return $this->get('notVAT195');
    }

    /**
     * Συμπληρώνοντας την ένδειξη του πεδίου notVAT195 (με την τιμή true) τα ποσά των
     * γραμμών του παραστατικού δε συμμετέχουν στη δήλωση ΦΠΑ (εκροές). Είναι
     * αποδεκτό μόνο για παραστατικά εσόδων των τύπων μεταξύ 1.1 – 11.5.
     *
     * @param  bool|null  $notVAT195  Ένδειξη μη συμμετοχής στο ΦΠΑ (έσοδα – εκροές)
     * @return $this
     * @version 1.0.9
     */
    public function setNotVAT195(?bool $notVAT195): static
    {
        return $this->set('notVAT195', $notVAT195);
    }

    public function set($key, $value): static
    {
        if (($key === 'expensesClassification' || $key === 'incomeClassification') && !is_array($value)) {
            return $this->push($key, $value);
        }

        return parent::set($key, $value);
    }
}