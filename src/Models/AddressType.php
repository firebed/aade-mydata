<?php

namespace Firebed\AadeMyData\Models;


class AddressType extends Type
{
    
    /**
     * @return string|null Οδός
     */
    public function getStreet(): ?string
    {
        return $this->get('street');
    }

    /**
     * <h2>Οδός</h2>
     *
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): self
    {
        return $this->put('street', $street);
    }

    /**
     * @return string|null Αριθμός Οδού
     */
    public function getNumber(): ?string
    {
        return $this->get('number');
    }

    /**
     * <h2>Αριθμός Οδού</h2>
     *
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): self
    {
        return $this->put('number', $number);
    }

    /**
     * @return string Ταχυδρομικός Κώδικας
     */
    public function getPostalCode(): string
    {
        return $this->get('postalCode');
    }

    /**
     * <h2>Ταχυδρομικός Κώδικας</h2>
     *
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode(string $postalCode): self
    {
        return $this->put('postalCode', $postalCode);
    }

    /**
     * @return string Πόλη
     */
    public function getCity(): string
    {
        return $this->get('city');
    }

    /**
     * <h2>Πόλη</h2>
     *
     * @param string $city Όνομα πόλης
     * @return $this
     */
    public function setCity(string $city): self
    {
        return $this->put('city', $city);
    }
}