<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.8
 */
class ProvidersSignature extends Type
{
    protected array $expectedOrder = [
        'SigningAuthor',
        'Signature',
        'EndToΕndReferenceID',
    ];
    
    public function __construct(string $signingAuthor = null, string $signature = null, ?string $endToEndReferenceID = null)
    {
        if ($signingAuthor !== null || $signature !== null || $endToEndReferenceID !== null) {
            parent::__construct([
                'SigningAuthor' => $signingAuthor,
                'Signature'     => $signature,
                'EndToΕndReferenceID' => $endToEndReferenceID,
            ]);
        }
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
    public function setSigningAuthor(string $signingAuthor): static
    {
        return $this->set('SigningAuthor', $signingAuthor);
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
    public function setSignature(string $signature): static
    {
        return $this->set('Signature', $signature);
    }

    /**
     * Το μοναδικό αναγνωριστικό αιτήματος πληρωμής(για πληρωμές IRIS - payment type = 8)
     *
     * @version 2.0.0
     */
    public function getEndToEndReferenceID(): ?string
    {
        return $this->get('EndToΕndReferenceID');
    }

    /**
     * Το μοναδικό αναγνωριστικό αιτήματος πληρωμής (για πληρωμές IRIS - payment type = 8)
     *
     * @version 2.0.0
     */
    public function setEndToEndReferenceID(?string $endToEndReferenceID): static
    {
        return $this->set('EndToΕndReferenceID', $endToEndReferenceID);
    }
}