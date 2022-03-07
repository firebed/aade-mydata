<?php

namespace Firebed\AadeMyData\Models;

class Address extends Type
{
    /**
     * @return string|null Οδός
     */
    public function getStreet(): ?string
    {
        return $this->get('street');
    }

    /**
     * @param string $street Οδός
     */
    public function setStreet(string $street): void
    {
        $this->put('street', $street);
    }

    /**
     * @return string|null Αριθμός Οδού
     */
    public function getNumber(): ?string
    {
        return $this->get('number');
    }

    /**
     * @param string $number Αριθμός Οδού
     */
    public function setNumber(string $number): void
    {
        $this->put('number', $number);
    }

    /**
     * @return string|null Ταχυδρομικός Κώδικας
     */
    public function getPostalCode(): ?string
    {
        return $this->get('postalCode');
    }

    /**
     * @param string $postalCode Ταχυδρομικός Κώδικας
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->put('postalCode', $postalCode);
    }

    /**
     * @return string|null Όνομα πόλης
     */
    public function getCity(): ?string
    {
        return $this->get('city');
    }

    /**
     * @param string $city Όνομα πόλης
     */
    public function setCity(string $city): void
    {
        $this->put('city', $city);
    }
    
}