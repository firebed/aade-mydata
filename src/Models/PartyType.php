<?php

namespace Firebed\AadeMyData\Models;


abstract class PartyType extends Type
{    
    /**
     * @return string ΑΦΜ
     */
    public function getVatNumber(): string
    {
        return $this->get('vatNumber');
    }

    /**
     * <h2>ΑΦΜ</h2>
     *
     * @param string $vatNumber Οποιοσδήποτε έγκυρος ΑΦΜ
     */
    public function setVatNumber(string $vatNumber): self
    {
        return $this->put('vatNumber', $vatNumber);
    }

    /**
     * @return string Κωδικός Χώρας
     */
    public function getCountry(): string
    {
        return $this->get('country');
    }

    /**
     * <h2>Κωδικός Χώρας</h2>
     *
     * <p>Ο κωδικός της χώρας είναι δύο χαρακτήρες και προέρχεται από την αντίστοιχη
     * λίστα χωρών όπως περιγράφεται στο ISO 3166.</p>
     *
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        return $this->put('country', $country);
    }

    /**
     * @return int Αριθμός Εγκατάστασης
     */
    public function getBranch(): int
    {
        return $this->get('branch');
    }

    /**
     * <h2>Αριθμός Εγκατάστασης</h2>
     *
     * <p>Σε περίπτωση που η εγκατάσταση του εκδότη είναι η έδρα ή δεν υφίσταται, το
     * πεδίο branch πρέπει να έχει την τιμή 0.</p>
     *
     * @param int $branch Ελάχιστη τιμή = 0
     */
    public function setBranch(int $branch): self
    {
        return $this->put('branch', $branch);
    }

    /**
     * @return string|null Επωνυμία
     */
    public function getName(): ?string
    {
        return $this->get('name');
    }

    /**
     * <h2>Επωνυμία</h2>
     *
     * <p>Για τον εκδότη, το πεδίο επωνυμίας δεν είναι αποδεκτό στην
     * περίπτωση που το παραστατικό αφορά οντότητα εντός Ελλάδας (GR).</p>
     *
     * <p>Για τον λήπτη, το πεδίο επωνυμίας δεν είναι αποδεκτό στην
     * περίπτωση που το παραστατικό αφορά οντότητα εντός Ελλάδας (GR).</p>
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        return $this->put('name', $name);
    }

    public function getAddress(): ?AddressType
    {
        return $this->get('address');
    }


    /**
     * <h2>Διεύθυνση</h2>
     *
     * <p>Για τον εκδότη, το πεδίο διεύθυνσης δεν είναι αποδεκτό στην
     * περίπτωση που το παραστατικό αφορά οντότητα εντός Ελλάδας (GR).</p>
     *
     * <p>Για τον λήπτη, το πεδίο διεύθυνσης δεν είναι αποδεκτό στην
     * περίπτωση που το παραστατικό αφορά οντότητα εντός Ελλάδας (GR).</p>
     *
     * @param AddressType $addressType
     * @return $this
     */
    public function setAddress(AddressType $addressType): self
    {
        return $this->put('address', $addressType);
    }
}