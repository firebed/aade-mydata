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
     * @return string|null Αριθμός Ακύρωσης
     */
    public function getCancellationMark(): ?string
    {
        return $this->get('cancellationMark');
    }

    /**
     * @return string|null Ημερομηνία Ακύρωσης
     */
    public function getCancellationDate(): ?string
    {
        return $this->get('cancellationDate');
    }
}