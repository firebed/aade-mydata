<?php

namespace Firebed\AadeMyData\Models;

class CancelledInvoice extends Type
{
    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης
     */
    public function getInvoiceMark(): ?string
    {
        return $this->get('invoiceMark');
    }

    /**
     * @param string $invoiceMark Μοναδικός Αριθμός Καταχώρησης
     */
    public function setInvoiceMark(string $invoiceMark): void
    {
        $this->set('invoiceMark', $invoiceMark);
    }

    /**
     * @return string|null Αριθμός Ακύρωσης
     */
    public function getCancellationMark(): ?string
    {
        return $this->get('cancellationMark');
    }

    /**
     * @param string $cancellationMark Αριθμός Ακύρωσης
     */
    public function setCancellationMark(string $cancellationMark): void
    {
        $this->set('cancellationMark', $cancellationMark);
    }

    /**
     * @return string|null Ημερομηνία Ακύρωσης
     */
    public function getCancellationDate(): ?string
    {
        return $this->get('cancellationDate');
    }

    /**
     * @param string $cancellationDate Ημερομηνία Ακύρωσης
     */
    public function setCancellationDate(string $cancellationDate): void
    {
        $this->set('cancellationDate', $cancellationDate);
    }

}