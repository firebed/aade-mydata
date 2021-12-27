<?php

namespace Firebed\AadeMyData\Models;

class CancelledInvoiceType extends Type
{
    public function setInvoiceMark(string $invoiceMark): self
    {
        return $this->put('invoiceMark', $invoiceMark);
    }

    public function getInvoiceMark()
    {
        return $this->get('invoiceMark');
    }

    public function setCancellationMark(string $cancellationMark): self
    {
        return $this->put('cancellationMark', $cancellationMark);
    }

    public function getCancellationMark()
    {
        return $this->get('cancellationMark');
    }

    public function setCancellationDate(string $cancellationDate): self
    {
        return $this->put('cancellationDate', $cancellationDate);
    }

    public function getCancellationDate()
    {
        return $this->get('cancellationDate');
    }
}