<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class Response extends Type
{
    protected array $casts = [
        'errors' => Errors::class,
    ];

    /**
     * @return int|null Αριθμός Σειράς Οντότητας εντός του υποβληθέντος xml
     * @version 2.0.1
     */
    public function getIndex(): ?int
    {
        return $this->get('index');
    }

    /**
     * @return string|null Κωδικός Αποτελέσματος
     * @version 2.0.1
     */
    public function getStatusCode(): ?string
    {
        return $this->get('statusCode');
    }

    /**
     * @return int|null Μοναδικός Αριθμός Εκκίνησης/Μεταφόρτωσης Διακίνησης
     * @version 2.0.1
     */
    public function getTransferMark(): ?int
    {
        return $this->get('transferMark');
    }

    /**
     * @return string|null Μοναδικός Αριθμός Απόρριψης Διακίνησης
     * @version 2.0.1
     */
    public function getRejectMark(): ?string
    {
        return $this->get('rejectMark');
    }

    /**
     * @return int|null Μοναδικός Αριθμός Καταχώρησης
     * @version 2.0.1
     */
    public function getDeliveryOutcomeMark(): ?int
    {
        return $this->get('deliveryOutcomeMark');
    }

    /**
     * @return Errors|null Λίστα Σφαλμάτων
     * @version 2.0.1
     */
    public function getErrors(): ?Errors
    {
        return $this->get('errors');
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()?->isNotEmpty() ?? false;
    }

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === 'Success';
    }

    public function isFailed(): bool
    {
        return $this->hasErrors();
    }

    public function __toString(): string
    {
        return $this->getStatusCode();
    }
}