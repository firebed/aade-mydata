<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.8
 */
class ECRToken extends Type
{
    protected array $expectedOrder = [
        'SigningAuthor',
        'SessionNumber',
    ];
    
    public function __construct(string $signingAuthor = null, string $sessionNumber = null)
    {
        if ($signingAuthor !== null || $sessionNumber !== null) {
            parent::__construct([
                'SigningAuthor' => $signingAuthor,
                'SessionNumber' => $sessionNumber
            ]);
        }
    }

    /**
     * @return string|null ECR id: Αριθμός μητρώου του φορολογικού μηχανισμού.
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
     * @param string $signingAuthor ECR id: Αριθμός μητρώου του φορολογικού μηχανισμού.
     * 
     * @version 1.0.8
     */
    public function setSigningAuthor(string $signingAuthor): static
    {
        return $this->set('SigningAuthor', $signingAuthor);
    }

    /**
     * @return string|null Μοναδικός 6-ψήφιος κωδικός που χαρακτηρίζει την κάθε συναλλαγή.
     * 
     * @version 1.0.8
     */
    public function getSessionNumber(): ?string
    {
        return $this->get('SessionNumber');
    }

    /**
     * Μοναδικός 6-ψήφιος κωδικός που χαρακτηρίζει την κάθε συναλλαγή.
     * 
     * @param string $sessionNumber Υπογραφή
     * 
     * @version 1.0.8
     */
    public function setSessionNumber(string $sessionNumber): static
    {
        return $this->set('SessionNumber', $sessionNumber);
    }
}