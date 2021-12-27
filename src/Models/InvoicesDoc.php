<?php

namespace Firebed\AadeMyData\Models;

class InvoicesDoc extends Type
{
    /**
     * @return InvoiceType[]
     */
    public function getInvoices(): array
    {
        return $this->attributes;
    }

    /**
     * @param InvoiceType $invoice
     * @return $this
     */
    public function addInvoice(InvoiceType $invoice): self
    {
        return $this->put('', $invoice);
    }

    public function put($key, $value): self
    {
        $this->attributes['invoice'][] = $value;
        return $this;
    }
}