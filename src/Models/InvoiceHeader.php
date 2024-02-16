<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;

class InvoiceHeader extends Type
{
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
     * @param string $series Σειρά παραστατικού
     */
    public function setSeries(string $series): void
    {
        $this->set('series', $series);
    }

    /**
     * @return string|null ΑΑ Παραστατικού, μέγιστο επιτρεπτό μήκος 50
     */
    public function getAa(): ?string
    {
        return $this->get('aa');
    }

    /**
     * @param string $aa ΑΑ Παραστατικού, μέγιστο επιτρεπτό μήκος 50
     */
    public function setAa(string $aa): void
    {
        $this->set('aa', $aa);
    }

    /**
     * @return string|null Ημερομηνία Έκδοσης Παραστατικού
     */
    public function getIssueDate(): ?string
    {
        return $this->get('issueDate');
    }

    /**
     * @param string $issueDate Ημερομηνία Έκδοσης Παραστατικού
     */
    public function setIssueDate(string $issueDate): void
    {
        $this->set('issueDate', $issueDate);
    }

    /**
     * @return string|null Είδος Παραστατικού
     */
    public function getInvoiceType(): ?string
    {
        return $this->get('invoiceType');
    }

    /**
     * @param InvoiceType|string $invoiceType Είδος Παραστατικού
     */
    public function setInvoiceType(InvoiceType|string $invoiceType): void
    {
        $this->set('invoiceType', $invoiceType);
    }

    /**
     * @return bool|null Αναστολή Καταβολής ΦΠΑ
     */
    public function isVatPaymentSuspension(): ?bool
    {
        return filter_var($this->get('vatPaymentSuspension'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param bool $vatPaymentSuspension Αναστολή Καταβολής ΦΠΑ
     */
    public function setVatPaymentSuspension(bool $vatPaymentSuspension): void
    {
        $this->set('vatPaymentSuspension', $vatPaymentSuspension);
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
     * @param string $currency Νόμισμα
     */
    public function setCurrency(string $currency): void
    {
        $this->set('currency', $currency);
    }

    /**
     * @return float|null Ισοτιμία
     */
    public function getExchangeRate(): ?float
    {
        return $this->get('exchangeRate');
    }

    /**
     * To πεδίο exchangeRate είναι η ισοτιμία του νομίσματος σε σχέση με το ευρώ.
     * Πρέπει να συμπληρώνεται μόνο όταν το νόμισμα δεν έχει τιμή EUR
     *
     * @param float $exchangeRate Ισοτιμία
     */
    public function setExchangeRate(float $exchangeRate): void
    {
        $this->set('exchangeRate', $exchangeRate);
    }

    /**
     * @return array|null Συσχετιζόμενα Παραστατικά (ΜΑΡΚ)
     */
    public function getCorrelatedInvoices(): ?array
    {
        return $this->get('correlatedInvoices');
    }

    /**
     * Το στοιχείο correlatedInvoices είναι λίστα και περιέχει τα ΜΑΡΚ των συσχετιζόμενων παραστατικών.
     *
     * @param int $correlatedInvoice Συσχετιζόμενο Παραστατικό (ΜΑΡΚ)
     */
    public function addCorrelatedInvoice(int $correlatedInvoice): void
    {
        $this->push('correlatedInvoices', $correlatedInvoice);
    }

    /**
     * @return bool|null Ένδειξη Αυτοτιμολόγησης
     */
    public function isSelfPricing(): ?bool
    {
        return filter_var($this->get('selfPricing'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param bool $selfPricing Ένδειξη Αυτοτιμολόγησης
     */
    public function setSelfPricing(bool $selfPricing): void
    {
        $this->set('selfPricing', $selfPricing);
    }

    /**
     * @return string|null Ημερομηνία Έναρξης Αποστολής
     */
    public function getDispatchDate(): ?string
    {
        return $this->get('dispatchDate');
    }

    /**
     * @param string $dispatchDate Ημερομηνία Έναρξης Αποστολής
     */
    public function setDispatchDate(string $dispatchDate): void
    {
        $this->set('dispatchDate', $dispatchDate);
    }

    /**
     * @return string|null Ώρα Έναρξης Αποστολής
     */
    public function getDispatchTime(): ?string
    {
        return $this->get('dispatchTime');
    }

    /**
     * @param string $dispatchTime Ώρα Έναρξης Αποστολής
     */
    public function setDispatchTime(string $dispatchTime): void
    {
        $this->set('dispatchTime', $dispatchTime);
    }

    /**
     * @return string|null Αριθμός Μεταφορικού Μέσου
     */
    public function getVehicleNumber(): ?string
    {
        return $this->get('vehicleNumber');
    }

    /**
     * @param string $vehicleNumber Αριθμός Μεταφορικού Μέσου
     */
    public function setVehicleNumber(string $vehicleNumber): void
    {
        $this->set('vehicleNumber', $vehicleNumber);
    }

    /**
     * @return string|null Σκοπός Διακίνησης
     */
    public function getMovePurpose(): ?string
    {
        return $this->get('movePurpose');
    }

    /**
     * @param MovePurpose|string $movePurpose Σκοπός Διακίνησης
     */
    public function setMovePurpose(MovePurpose|string $movePurpose): void
    {
        $this->set('movePurpose', $movePurpose);
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
     * @param bool $fuelInvoice Ένδειξη Παραστατικό καυσίμων
     */
    public function setFuelInvoice(bool $fuelInvoice): void
    {
        $this->set('fuelInvoice', $fuelInvoice);
    }

    /**
     * @return int|null Ειδική Κατηγορία Παραστατικού
     */
    public function getSpecialInvoiceCategory(): ?int
    {
        return $this->get('specialInvoiceCategory');
    }

    /**
     * Οι πιθανές τιμές του πεδίου specialInvoiceCategory περιγράφονται αναλυτικά στον
     * αντίστοιχα πίνακα του Παραρτήματος.
     *
     * @param SpecialInvoiceCategory|int $specialInvoiceCategory Ελάχιστη τιμή = 1, Μέγιστη τιμή = 10
     * @return void
     */
    public function setSpecialInvoiceCategory(SpecialInvoiceCategory|int $specialInvoiceCategory): void
    {
        $this->set('specialInvoiceCategory', $specialInvoiceCategory);
    }

    /**
     * @return int|null Τύπος Απόκλισης (Διαφοροποίησης) Παραστατικού
     */
    public function getInvoiceVariationType(): ?int
    {
        return $this->get('invoiceVariationType');
    }

    /**
     * Οι πιθανές τιμές του πεδίου invoiceVariationType περιγράφονται αναλυτικά στον
     * αντίστοιχα πίνακα του Παραρτήματος. Επίσης, λεπτομέρειες σχετικά με τον τρόπο
     * χρήσης τους από επιχειρησιακής σκοπιάς περιγράφονται στο σχετικό επιχειρησιακό
     * έγγραφο. (Δεν επιτρέπεται στην περίπτωση αποστολής μέσω παρόχων)
     * @param InvoiceVariationType|int $invoiceVariationType Ελάχιστη τιμή = 1, Μέγιστη τιμή = 4
     * @return void
     */
    public function setInvoiceVariationType(InvoiceVariationType|int $invoiceVariationType): void
    {
        $this->set('invoiceVariationType', $invoiceVariationType);
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
     * Λοιπές συσχετιζόμενες οντότητες
     *
     * @param EntityType $entityType
     *
     * @version 1.0.7
     */
    public function addOtherCorrelatedEntities(EntityType $entityType): void
    {
        $this->push('otherCorrelatedEntities', $entityType);
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
     * @param OtherDeliveryNoteHeader|null $otherDeliveryNoteHeader Λοιπά Γενικά Στοιχεία Διακίνησης.
     * @version 1.0.8
     */
    public function setOtherDeliveryNoteHeader(?OtherDeliveryNoteHeader $otherDeliveryNoteHeader): void
    {
        $this->set('otherDeliveryNoteHeader', $otherDeliveryNoteHeader);
    }

    /**
     * @return bool Ένδειξη Παραστατικού Διακίνησης
     * @version 1.0.8
     */
    public function getIsDeliveryNote(): bool
    {
        return filter_var($this->get('isDeliveryNote', false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Το πεδίο isDeliveryNote ορίζει αν πρόκειται για τιμολόγιο που είναι και δελτίο
     * αποστολής (π.χ το παραστατικό τύπου 1.1 - Τιμολόγιο Πώλησης, εφόσον φέρει την
     * ένδειξη isDeliveryNote = true, τότε είναι και δελτίο διακίνησης και θα πρέπει να
     * αποσταλούν και επιπλέον στοιχεία διακίνησης).
     *
     * @param bool $isDeliveryNote Ένδειξη Παραστατικού Διακίνησης
     * @version 1.0.8
     */
    public function setIsDeliveryNote(bool $isDeliveryNote): void
    {
        $this->set('isDeliveryNote', $isDeliveryNote);
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
     * @param string|null $otherMovePurposeTitle Τίτλος της Λοιπής Αιτίας Διακίνησης
     * @version 1.0.8
     */
    public function setOtherMovePurposeTitle(?string $otherMovePurposeTitle): void
    {
        $this->set('otherMovePurposeTitle', $otherMovePurposeTitle);
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
     * @param bool $thirdPartyCollection Ένδειξη Είσπραξης Τρίτων
     * @version 1.0.8
     */
    public function setThirdPartyCollection(bool $thirdPartyCollection): void
    {
        $this->set('thirdPartyCollection', $thirdPartyCollection);
    }

    public function set($key, $value): void
    {
        if ($key === 'correlatedInvoices' || $key === 'otherCorrelatedEntities') {
            $this->push($key, $value);
            return;
        }

        parent::set($key, $value);
    }
}