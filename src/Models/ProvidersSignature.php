<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.8
 */
class ProvidersSignature extends Type
{
    public function __construct(string $signingAuthor = null, string $signature = null)
    {
        $attributes = compact('signingAuthor', 'signature');
        $this->setAttributes(array_filter($attributes, fn($attr) => !is_null($attr)));
    }

    /**
     * @return string|null Αριθμός Απόφασης έγκρισης ΥΠΑΗΕΣ Παρόχου
     * 
     * @version 1.0.8
     */
    public function getSigningAuthor(): ?string
    {
        return $this->get('SigningAuthor');
    }

    /**
     * Μέγιστο επιτρεπτό μήκος 20
     * 
     * @param string $signingAuthor Αριθμός Απόφασης έγκρισης ΥΠΑΗΕΣ Παρόχου
     * 
     * @version 1.0.8
     */
    public function setSigningAuthor(string $signingAuthor): void
    {
        $this->set('SigningAuthor', $signingAuthor);
    }

    /**
     * @return string|null Υπογραφή
     * 
     * @version 1.0.8
     */
    public function getSignature(): ?string
    {
        return $this->get('Signature');
    }

    /**
     * Λεπτομέρειες στην υπ’ αριθμ. Α. 1155/09-10-* 2023 απόφαση
     * (ΦΕΚ 5992 myDATA REST API 30 Β΄/13.10.2023)
     * 
     * @param string $signature Υπογραφή
     * 
     * @version 1.0.8
     */
    public function setSignature(string $signature): void
    {
        $this->set('Signature', $signature);
    }
}