<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Traits\HasFactory;

class Party extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'vatNumber',
        'country',
        'branch',
        'name',
        'address',
        'documentIdNo',
        'supplyAccountNo',
        'countryDocumentId'
    ];

    protected array $casts = [
        'address' => Address::class,
    ];

    /**
     * @return string|null Οποιοσδήποτε έγκυρος ΑΦΜ
     */
    public function getVatNumber(): ?string
    {
        return $this->get('vatNumber');
    }

    /**
     * @param  string  $vatNumber  Οποιοσδήποτε έγκυρος ΑΦΜ
     */
    public function setVatNumber(string $vatNumber): static
    {
        return $this->set('vatNumber', $vatNumber);
    }

    /**
     * @return string|null Ο κωδικός της χώρας
     */
    public function getCountry(): ?string
    {
        return $this->get('country');
    }

    /**
     * Ο κωδικός της χώρας είναι δύο χαρακτήρες και προέρχεται
     * από την αντίστοιχη λίστα χωρών όπως περιγράφεται στο ISO 3166
     *
     * @param  CountryCode|string  $country  Ο κωδικός της χώρας
     * @return Party
     */
    public function setCountry(CountryCode|string $country): static
    {
        if ($country instanceof CountryCode) {
            $country = $country->value;
        }
        
        return $this->set('country', $country);
    }

    /**
     * @return int|null Αριθμός Εγκατάστασης
     */
    public function getBranch(): ?int
    {
        return $this->get('branch');
    }

    /**
     * <p>Αριθμός Εγκατάστασης</p>
     * <p>Σε περίπτωση που η εγκατάσταση του εκδότη είναι η έδρα ή δεν υφίσταται,
     * το πεδίο branch πρέπει να έχει την τιμή 0</p>
     *
     * @param  int  $branch  Ελάχιστη τιμή = 0
     */
    public function setBranch(int $branch): static
    {
        return $this->set('branch', $branch);
    }

    /**
     * @return string|null Επωνυμία
     */
    public function getName(): ?string
    {
        return $this->get('name');
    }

    /**
     * <ul>
     * <li>Για τον εκδότη, το πεδίο επωνυμίας δεν είναι αποδεκτό στην περίπτωση που
     * το παραστατικό αφορά οντότητα εντός Ελλάδας (GR)</li>
     * <li>Για τον λήπτη, το πεδίο επωνυμίας δεν είναι αποδεκτό στην περίπτωση που
     * το παραστατικό αφορά οντότητα εντός Ελλάδας (GR)</li>
     * </ul>
     *
     * @param  string|null  $name  Επωνυμία
     */
    public function setName(?string $name): static
    {
        return $this->set('name', $name);
    }

    /**
     * @return Address|null Διεύθυνση
     */
    public function getAddress(): ?Address
    {
        return $this->get('address');
    }

    /**
     * <ul>
     * <li>Για τον εκδότη, το πεδίο διεύθυνσης δεν είναι αποδεκτό στην περίπτωση που
     * το παραστατικό αφορά οντότητα εντός Ελλάδας (GR)</li>
     * <li>Για τον λήπτη, το πεδίο διεύθυνσης δεν είναι αποδεκτό στην περίπτωση που
     * το παραστατικό αφορά οντότητα εντός Ελλάδας (GR)</li>
     * </ul>
     *
     * @param  Address|null  $address  Διεύθυνση
     */
    public function setAddress(?Address $address): static
    {
        return $this->set('address', $address);
    }

    /**
     * @return string|null Ο αριθμός επίσημου εγγράφου
     */
    public function getDocumentIdNo(): ?string
    {
        return $this->get('documentIdNo');
    }

    /**
     *<ul>
     * <li>Ο αριθμός επίσημου εγγράφου, είναι επιτρεπτός μόνο στην περίπτωση διαβίβασης
     * παραστατικών που ανήκουν στην Ειδική Κατηγορία Παραστατικού Tax free (το
     * πεδίο της επικεφαλίδας του παραστατικού specialInvoiceCategory έχει την τιμή 4),
     * και μπορεί να είναι οποιοδήποτε επίσημο έγγραφο ταυτοποίησης (π.χ αριθμός
     * διαβατηρίου) του λήπτη του παραστατικού.</li>
     *
     * <li>Μέγιστο επιτρεπτό μήκος 100. Έγκυρο μόνο στην περίπτωση παραστατικού tax free
     * (specialInvoiceCategory = 4)</li>
     * </ul>
     *
     * @param  string|null  $documentIdNo
     * @return Party
     */
    public function setDocumentIdNo(?string $documentIdNo): static
    {
        return $this->set('documentIdNo', $documentIdNo);
    }

    /**
     * @return string|null Ο αριθμός Παροχής Ηλ. Ρεύματος
     */
    public function getSupplyAccountNo(): ?string
    {
        return $this->get('supplyAccountNo');
    }

    /**
     * <ul>
     * <li>Ο αριθμός Παροχής Ηλ. Ρεύματος, είναι επιτρεπτός μόνο στην περίπτωση
     * διαβίβασης παραστατικών καυσίμων (το πεδίο της επικεφαλίδας του παραστατικού
     * fuelInvoice έχει την τιμή true).</li>
     *
     * <li>Μέγιστο επιτρεπτό μήκος 100. Έγκυρο μόνο στην περίπτωση παραστατικών καυσίμων.</li>
     * </ul>
     *
     * @param  string|null  $supplyAccountNo
     * @return Party
     */
    public function setSupplyAccountNo(?string $supplyAccountNo): static
    {
        return $this->set('supplyAccountNo', $supplyAccountNo);
    }

    /**
     * @return string|null Κωδ. Χώρας Έκδοσης Επίσημου Εγγράφου
     * @version 1.0.7
     */
    public function getCountryDocumentId(): ?string
    {
        return $this->get('countryDocumentId');
    }

    /**
     * Ο κωδικός χώρας έκδοσης του επίσημου εγγράφου (π.χ διαβατηρίου), είναι
     * επιτρεπτός μόνο στην περίπτωση διαβίβασης παραστατικών που ανήκουν στην
     * Ειδική Κατηγορία Παραστατικού Tax free (το πεδίο της επικεφαλίδας του
     * παραστατικού specialInvoiceCategory έχει την τιμή 4) και εφόσον έχει συμπληρωθεί
     * το πεδίο αριθμός επίσημου εγγράφου (documentIdNo) και αφορά τον λήπτη του
     * παραστατικού.
     *
     * @param  CountryCode|string|null  $countryDocumentId  Κωδ. Χώρας Έκδοσης Επίσημου Εγγράφου
     * @return Party
     * @version 1.0.7
     */
    public function setCountryDocumentId(CountryCode|string|null $countryDocumentId): static
    {
        if ($countryDocumentId instanceof CountryCode) {
            $countryDocumentId = $countryDocumentId->value;
        }
        
        return $this->set('countryDocumentId', $countryDocumentId);
    }
}