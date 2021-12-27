<?php

namespace Firebed\AadeMyData\Models;

class TaxesTotals extends Type
{
    public function addTaxes(TaxTotalsType $taxes): self
    {
        return $this->put('', $taxes);
    }

    public function put($key, $value): self
    {
        $this->attributes[] = $value;
        return $this;
    }
}