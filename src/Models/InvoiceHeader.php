<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\CurrencyCode;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Traits\HasFactory;

class InvoiceHeader extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'series',
        'aa',
        'issueDate',
        'invoiceType',
        'vatPaymentSuspension',
        'currency',
        'exchangeRate',
        'correlatedInvoices',
        'selfPricing',
        'dispatchDate',
        'dispatchTime',
        'vehicleNumber',
        'movePurpose',
        'fuelInvoice',
        'specialInvoiceCategory',
        'invoiceVariationType',
        'otherCorrelatedEntities',
        'otherDeliveryNoteHeader',
        'isDeliveryNote',
        'otherMovePurposeTitle',
        'thirdPartyCollection',
        'multipleConnectedMarks',
        'tableAA',
        'totalCancelDeliveryOrders',
    ];

    protected array $casts = [
        'invoiceType' => InvoiceType::class,
        'movePurpose' => MovePurpose::class,
        'specialInvoiceCategory' => SpecialInvoiceCategory::class,
        'invoiceVariationType' => InvoiceVariationType::class,
        'otherCorrelatedEntities' => EntityType::class,
        'otherDeliveryNoteHeader' => OtherDeliveryNoteHeader::class,
    ];

    /**
     * @return string|null Σειρά παραστατικού
     */
    public function getSeries(): ?string
    {
        return $this->get('series');
    }

    /**
     * Σε περίπτωση μή έκδοσης σειράς παραστατικού, το πεδίο series πρέπει να έχει την τιμή 0
     *
     * @param  string  $series  Σειρά παραστατικού
     */
    public function setSeries(string $series): static
    {
        return $this->set('series', $series);
    }

    /**
     * @return string|null ΑΑ Παραστατικού, μέγιστο επιτρεπτό μήκος 50
     */
    public function getAa(): ?string
    {
        return $this->get('aa');
    }

    /**
     * @param  string  $aa  ΑΑ Παραστατικού, μέγιστο επιτρεπτό μήκος 50
     */
    public function setAa(string $aa): static
    {
        return $this->set('aa', $aa);
    }

    /**
     * @return string|null Ημερομηνία Έκδοσης Παραστατικού (Y-m-d)
     */
    public function getIssueDate(): ?string
    {
        return $this->get('issueDate');
    }

    /**
     * @param  string  $issueDate  Ημερομηνία Έκδοσης Παραστατικού (Y-m-d)
     */
    public function setIssueDate(string $issueDate): static
    {
        return $this->set('issueDate', $issueDate);
    }

    /**
     * @return InvoiceType|null Είδος Παραστατικού
     */
    public function getInvoiceType(): ?InvoiceType
    {
        return $this->get('invoiceType');
    }

    /**
     * @param  InvoiceType|string  $invoiceType  Είδος Παραστατικού
     */
    public function setInvoiceType(InvoiceType|string $invoiceType): static
    {
        return $this->set('invoiceType', $invoiceType);
    }

    /**
     * @return bool|null Αναστολή Καταβολής ΦΠΑ
     */
    public function isVatPaymentSuspension(): ?bool
    {
        return filter_var($this->get('vatPaymentSuspension'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param  bool|null  $vatPaymentSuspension  Αναστολή Καταβολής ΦΠΑ
     */
    public function setVatPaymentSuspension(?bool $vatPaymentSuspension): static
    {
        return $this->set('vatPaymentSuspension', $vatPaymentSuspension);
    }

    /**
     * @return string|null Νόμισμα
     */
    public function getCurrency(): ?string
    {
        return $this->get('currency');
    }

    /**
     * Ο κωδικός νομισμάτων προέρχεται από την αντίστοιχη λίστα σύμφωνα με το πρότυπο ISO4217.
     *
     * @param  CurrencyCode|string  $currency  Νόμισμα
     * @return InvoiceHeader
     */
    public function setCurrency(CurrencyCode|string $currency): static
    {
        if ($currency instanceof CurrencyCode) {
            $currency = $currency->value;
        }
        
        return $this->set('currency', $currency);
    }

    /**
     * @return float|null Ισοτιμία
     */
    public function getExchangeRate(): ?float
    {
        return $this->get('exchangeRate');
    }

    /**
     * <p>To πεδίο exchangeRate είναι η ισοτιμία του νομίσματος σε σχέση με το ευρώ.
     * Πρέπει να συμπληρώνεται μόνο όταν το νόμισμα δεν έχει τιμή EUR.</p>
     * <p>Ελάχιστη τιμή = 0</p>
     * <p>Δεκαδικά ψηφία = 5</p>
     * @param  float|null  $exchangeRate  Ισοτιμία
     */
    public function setExchangeRate(?float $exchangeRate): static
    {
        return $this->set('exchangeRate', $exchangeRate);
    }

    /**
     * @return array|null Συσχετιζόμενα Παραστατικά (ΜΑΡΚ)
     */
    public function getCorrelatedInvoices(): ?array
    {
        return $this->get('correlatedInvoices');
    }

    /**
     * @param  int  $correlatedInvoice  Συσχετιζόμενο Παραστατικό (ΜΑΡΚ)
     */
    public function addCorrelatedInvoice(int $correlatedInvoice): static
    {
        return $this->push('correlatedInvoices', $correlatedInvoice);
    }

    /**
     * @param  int[]|null  $correlatedInvoices  Συσχετιζόμενα Παραστατικά (ΜΑΡΚ)
     */
    public function setCorrelatedInvoices(?array $correlatedInvoices): static
    {
        return $this->set('correlatedInvoices', $correlatedInvoices);
    }

    /**
     * @return bool|null Ένδειξη Αυτοτιμολόγησης
     */
    public function isSelfPricing(): ?bool
    {
        return filter_var($this->get('selfPricing'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param  bool|null  $selfPricing  Ένδειξη Αυτοτιμολόγησης
     */
    public function setSelfPricing(?bool $selfPricing): static
    {
        return $this->set('selfPricing', $selfPricing);
    }

    /**
     * @return string|null Ημερομηνία Έναρξης Αποστολής
     */
    public function getDispatchDate(): ?string
    {
        return $this->get('dispatchDate');
    }

    /**
     * @param  string|null  $dispatchDate  Ημερομηνία Έναρξης Αποστολής
     */
    public function setDispatchDate(?string $dispatchDate): static
    {
        return $this->set('dispatchDate', $dispatchDate);
    }

    /**
     * @return string|null Ώρα Έναρξης Αποστολής
     */
    public function getDispatchTime(): ?string
    {
        return $this->get('dispatchTime');
    }

    /**
     * @param  string|null  $dispatchTime  Ώρα Έναρξης Αποστολής hh:mm:ss
     */
    public function setDispatchTime(?string $dispatchTime): static
    {
        return $this->set('dispatchTime', $dispatchTime);
    }

    /**
     * @return string|null Αριθμός Μεταφορικού Μέσου
     */
    public function getVehicleNumber(): ?string
    {
        return $this->get('vehicleNumber');
    }

    /**
     * @param  string|null  $vehicleNumber  Αριθμός Μεταφορικού Μέσου
     */
    public function setVehicleNumber(?string $vehicleNumber): static
    {
        return $this->set('vehicleNumber', $vehicleNumber);
    }

    /**
     * @return MovePurpose|null Σκοπός Διακίνησης
     */
    public function getMovePurpose(): ?MovePurpose
    {
        return $this->get('movePurpose');
    }

    /**
     * @param  MovePurpose|string|null  $movePurpose  Σκοπός Διακίνησης
     */
    public function setMovePurpose(MovePurpose|string|null $movePurpose): static
    {
        return $this->set('movePurpose', $movePurpose);
    }

    /**
     * @return bool|null Ένδειξη Παραστατικό καυσίμων
     */
    public function isFuelInvoice(): ?bool
    {
        return filter_var($this->get('fuelInvoice'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Το πεδίο fuelInvoice ορίζει αν πρόκειται για παραστατικό πώλησης υγρών καυσίμων και
     * επιτρέπεται η αποστολή μόνο για την περίπτωση των παρόχων
     *
     * @param  bool|null  $fuelInvoice  Ένδειξη Παραστατικό καυσίμων
     */
    public function setFuelInvoice(?bool $fuelInvoice): static
    {
        return $this->set('fuelInvoice', $fuelInvoice);
    }

    /**
     * @return SpecialInvoiceCategory|null Ειδική Κατηγορία Παραστατικού
     */
    public function getSpecialInvoiceCategory(): ?SpecialInvoiceCategory
    {
        return $this->get('specialInvoiceCategory');
    }

    /**
     * Οι πιθανές τιμές του πεδίου specialInvoiceCategory περιγράφονται αναλυτικά στον
     * αντίστοιχα πίνακα του Παραρτήματος.
     *
     * @param  SpecialInvoiceCategory|int|null  $specialInvoiceCategory  Ελάχιστη τιμή = 1, Μέγιστη τιμή = 10
     */
    public function setSpecialInvoiceCategory(SpecialInvoiceCategory|int|null $specialInvoiceCategory): static
    {
        return $this->set('specialInvoiceCategory', $specialInvoiceCategory);
    }

    /**
     * @return InvoiceVariationType|null Τύπος Απόκλισης (Διαφοροποίησης) Παραστατικού
     */
    public function getInvoiceVariationType(): ?InvoiceVariationType
    {
        return $this->get('invoiceVariationType');
    }

    /**
     * Οι πιθανές τιμές του πεδίου invoiceVariationType περιγράφονται αναλυτικά στον
     * αντίστοιχα πίνακα του Παραρτήματος. Επίσης, λεπτομέρειες σχετικά με τον τρόπο
     * χρήσης τους από επιχειρησιακής σκοπιάς περιγράφονται στο σχετικό επιχειρησιακό
     * έγγραφο. (Δεν επιτρέπεται στην περίπτωση αποστολής μέσω παρόχων)
     * @param  InvoiceVariationType|int|null  $invoiceVariationType  Ελάχιστη τιμή = 1, Μέγιστη τιμή = 4
     */
    public function setInvoiceVariationType(InvoiceVariationType|int|null $invoiceVariationType): static
    {
        return $this->set('invoiceVariationType', $invoiceVariationType);
    }

    /**
     * @return EntityType[]|null Λοιπές συσχετιζόμενες οντότητες
     *
     * @version 1.0.7
     */
    public function getOtherCorrelatedEntities(): ?array
    {
        return $this->get('otherCorrelatedEntities');
    }

    /**
     * @param  EntityType  $entityType  Λοιπές συσχετιζόμενες οντότητες
     *
     * @version 1.0.7
     */
    public function addOtherCorrelatedEntities(EntityType $entityType): static
    {
        return $this->push('otherCorrelatedEntities', $entityType);
    }

    /**
     * @param  EntityType[]|null  $entities  Λοιπές συσχετιζόμενες οντότητες
     *
     * @version 1.0.7
     */
    public function setOtherCorrelatedEntities(?array $entities): static
    {
        return $this->set('otherCorrelatedEntities', $entities);
    }

    /**
     * @return  OtherDeliveryNoteHeader|null Λοιπά Γενικά Στοιχεία Διακίνησης.
     * @version 1.0.8
     */
    public function getOtherDeliveryNoteHeader(): ?OtherDeliveryNoteHeader
    {
        return $this->get('otherDeliveryNoteHeader');
    }

    /**
     * @param  OtherDeliveryNoteHeader|null  $otherDeliveryNoteHeader  Λοιπά Γενικά Στοιχεία Διακίνησης.
     * @version 1.0.8
     */
    public function setOtherDeliveryNoteHeader(?OtherDeliveryNoteHeader $otherDeliveryNoteHeader): static
    {
        return $this->set('otherDeliveryNoteHeader', $otherDeliveryNoteHeader);
    }

    /**
     * @return bool Ένδειξη Παραστατικού Διακίνησης
     * @version 1.0.8
     */
    public function getIsDeliveryNote(): bool
    {
        return filter_var($this->get('isDeliveryNote'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Το πεδίο isDeliveryNote ορίζει αν πρόκειται για τιμολόγιο που είναι και δελτίο
     * αποστολής (π.χ το παραστατικό τύπου 1.1 - Τιμολόγιο Πώλησης, εφόσον φέρει την
     * ένδειξη isDeliveryNote = true, τότε είναι και δελτίο διακίνησης και θα πρέπει να
     * αποσταλούν και επιπλέον στοιχεία διακίνησης).
     *
     * @param  bool|null  $isDeliveryNote  Ένδειξη Παραστατικού Διακίνησης
     * @version 1.0.8
     */
    public function setIsDeliveryNote(?bool $isDeliveryNote): static
    {
        return $this->set('isDeliveryNote', $isDeliveryNote);
    }

    /**
     * @return string|null Τίτλος της Λοιπής Αιτίας Διακίνησης
     * @version 1.0.8
     */
    public function getOtherMovePurposeTitle(): ?string
    {
        return $this->get('otherMovePurposeTitle');
    }

    /**
     * Το πεδίο otherMovePurposeTitle συμπληρώνεται όταν έχει επιλεγεί ως
     * movePurpose = 19 (Λοιπές Διακινήσεις) και ορίζει τον τίτλο της άλλης διακίνησης.
     *
     * @param  string|null  $otherMovePurposeTitle  Τίτλος της Λοιπής Αιτίας Διακίνησης
     * @version 1.0.8
     */
    public function setOtherMovePurposeTitle(?string $otherMovePurposeTitle): static
    {
        return $this->set('otherMovePurposeTitle', $otherMovePurposeTitle);
    }

    /**
     * @return bool Ένδειξη Είσπραξης Τρίτων
     * @version 1.0.8
     */
    public function getThirdPartyCollection(): bool
    {
        return filter_var($this->get('thirdPartyCollection'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Το πεδίο thirdPartyCollection ορίζει αν η επιχείρηση που κάνει χρήση Μέσων
     * Πληρωμών ως Χρήστης υπηρεσιών πληρωμών και εισπράττει για λογαριασμό
     * τρίτων (περίπτωση παραστατικού 8.4 - Απόδειξη Είσπραξης POS) ή αν επιστρέφει
     * ποσά συναλλαγής για λογαριασμό τρίτων (περίπτωση παραστατικού 8.5 - Απόδειξη
     * Επιστροφής POS).
     *
     * @param  bool|null  $thirdPartyCollection  Ένδειξη Είσπραξης Τρίτων
     * @version 1.0.8
     */
    public function setThirdPartyCollection(?bool $thirdPartyCollection): static
    {
        return $this->set('thirdPartyCollection', $thirdPartyCollection);
    }

    /**
     * @return array|null Πολλαπλά Συνδεόμενα MARKs
     * @version 1.0.9
     */
    public function getMultipleConnectedMarks(): ?array
    {
        return $this->get('multipleConnectedMarks');
    }

    /**
     * @param  int  $multipleConnectedMark  Πολλαπλά Συνδεόμενα MARKs
     * 
     * @return $this
     * @version 1.0.9
     */
    public function addMultipleConnectedMark(int $multipleConnectedMark): static
    {
        return $this->push('multipleConnectedMarks', $multipleConnectedMark);
    }

    /**
     * Το πεδίο multipleConnectedMarks είναι λίστα που περιέχει πολλαπλά συνδεόμενα
     * ΜΑΡΚs (π.χ. μπορεί να χρησιμοποιηθεί κατά έκδοση τιμολογίου/απόδειξης για
     * πολλά δελτία παραγγελιών εστίασης).
     * 
     * Δεν είναι αποδεκτό για τα παραστατικά των τύπων 1.6, 2.4 και 5.1.
     * 
     * @param  array|null  $multipleConnectedMarks Πολλαπλά Συνδεόμενα MARKs
     * @return $this
     * @version 1.0.9
     */
    public function setMultipleConnectedMarks(?array $multipleConnectedMarks): static
    {
        return $this->set('multipleConnectedMarks', $multipleConnectedMarks);
    }

    /**
     * @return string|null ΑΑ Τραπεζιού
     * @version 1.0.9
     */
    public function getTableAA(): ?string
    {
        return $this->get('tableAA');
    }

    /**
     * Αποδεκτό μόνο για παραστατικό τύπου 8.6.
     * Μέγιστο επιτρεπτό μήκος 50
     * 
     * @param  string  $tableAA ΑΑ Τραπεζιού
     * @return $this
     * @version 1.0.9
     */
    public function setTableAA(string $tableAA): static
    {
        return $this->set('tableAA', $tableAA);
    }

    /**
     * @return bool|null Ένδειξη συνολικής αναίρεσης Δελτίων Παραγγελίας
     * @version 1.0.9
     */
    public function getTotalCancelDeliveryOrders(): ?bool
    {
        return $this->get('totalCancelDeliveryOrders');
    }

    /**
     * Το πεδίο totalCancelDeliveryOrders είναι έγκυρο μόνο για παραστατικά τύπου 8.6
     * (δελτίο παραγγελίας εστίασης) και ορίζει αν το παραστατικό αναιρεί («ακυρώνει»)
     * συνολικά τα MARKs των δελτίων παραγγελιών εστίασης που έχουν δηλωθεί στο
     * πεδίο multipleConnectedMarks (σε αυτήν την περίπτωση στο πεδίο
     * multipleConnectedMarks πρέπει να δηλωθούν MARKs των δελτίων παραγγελίας
     * εστίασης – τύπου 8.6 – για τα οποία θα γίνει συνολική αναίρεση τους). Στην
     * περίπτωση αυτή το παραστατικό πρέπει να διαβιβάζεται με μια μόνο γραμμή με
     * μηδενικές αξίες και κατηγορία ΦΠΑ την 8 (Εγγραφές χωρίς ΦΠΑ).
     * 
     * @param  bool|null  $totalCancelDeliveryOrders Ένδειξη συνολικής αναίρεσης Δελτίων Παραγγελίας
     * @return $this
     * @version 1.0.9
     */
    public function setTotalCancelDeliveryOrders(?bool $totalCancelDeliveryOrders): static
    {
        return $this->set('totalCancelDeliveryOrders', $totalCancelDeliveryOrders);
    }

    public function set($key, $value): static
    {
        if (in_array($key, ['correlatedInvoices', 'otherCorrelatedEntities', 'multipleConnectedMarks']) && !is_array($value)) {
            return $this->push($key, $value);
        }

        return parent::set($key, $value);
    }
}