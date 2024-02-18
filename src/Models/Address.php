<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

class Address extends Type
{
    use HasFactory;
    
    protected array $expectedOrder = [
        'street',
        'number',
        'postalCode',
        'city'
    ];
    
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
        $this->set('street', $street);
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
        $this->set('number', $number);
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
        $this->set('postalCode', $postalCode);
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
        $this->set('city', $city);
    }
    
}