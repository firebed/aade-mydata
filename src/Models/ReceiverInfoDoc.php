<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * What RequestReceiverInfo answers for an entity: the providers it invoices through and
 * the emails at which it accepts invoices from e-invoicing (ReceiverInfoDoc).
 */
class ReceiverInfoDoc extends Model
{
    protected function casts(): array
    {
        return [
            'receptionProviders' => ReceptionProviders::class,
            'receptionEmails' => ReceptionEmails::class,
        ];
    }

    /**
     * @return ReceptionProviders|null Λίστα Παρόχων
     */
    public function getReceptionProviders(): ?ReceptionProviders
    {
        return $this->get('receptionProviders');
    }

    /**
     * @return ReceptionEmails|null Λίστα Email παραλαβής
     */
    public function getReceptionEmails(): ?ReceptionEmails
    {
        return $this->get('receptionEmails');
    }
}
