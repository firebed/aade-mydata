<?php

namespace Firebed\AadeMyData\Models;

abstract class Party extends Type
{
    /**
     * @return string|null Οποιοσδήποτε έγκυρος ΑΦΜ
     */
    public function getVatNumber(): ?string
    {
        return $this->get('vatNumber');
    }

    /**
     * @param string $vatNumber Οποιοσδήποτε έγκυρος ΑΦΜ
     */
    public function setVatNumber(string $vatNumber): void
    {
        $this->put('vatNumber', $vatNumber);
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
     * @param string $country Ο κωδικός της χώρας
     */
    public function setCountry(string $country): void
    {
        $this->put('country', $country);
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
     * @param int $branch Ελάχιστη τιμή = 0
     */
    public function setBranch(int $branch): void
    {
        $this->put('branch', $branch);
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
     * @param string $name Επωνυμία
     */
    public function setName(string $name): void
    {
        $this->put('name', $name);
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
     * @param Address $address Διεύθυνση
     */
    public function setAddress(Address $address): void
    {
        $this->put('address', $address);
    }
}