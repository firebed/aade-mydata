<?php

namespace Firebed\AadeMyData\Models;

/**
 * Free key => value pairs that travel with the model but are not part of myDATA: the
 * attribute is deliberately absent from every $expectedOrder, so it is never written to
 * the InvoicesDoc XML, yet it stays on the model (toArray(), make()). Gateways that
 * transmit through an e-invoicing provider forward it as the provider's extra fields.
 */
trait HasExtraFields
{
    /**
     * @return array<string, mixed>|null
     */
    public function getExtraFields(): ?array
    {
        return $this->get('extraFields');
    }

    /**
     * @param array<string, mixed>|null $extraFields
     */
    public function setExtraFields(?array $extraFields): static
    {
        return $this->set('extraFields', $extraFields);
    }

    public function addExtraField(string $key, mixed $value): static
    {
        $extraFields = $this->getExtraFields() ?? [];
        $extraFields[$key] = $value;

        return $this->set('extraFields', $extraFields);
    }
}
