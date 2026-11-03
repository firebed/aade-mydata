<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Enums\Statements\RecallStatus;
use Firebed\AadeMyData\Models\Model;

/**
 * @version 1.0.12
 */
class RecalledStatementType extends Model
{
    protected array $expectedOrder = [
        'statementID',
        'entityVatNumber',
        'recallId',
        'recallStatus',
        'recallDate',
        'transactionDate',
        'recallVatNumber',
    ];

    protected function casts(): array
    {
        return [
            'recallStatus' => RecallStatus::class,
        ];
    }

    /**
     * @return int|null Μοναδικός Κωδικός Αρχικής Δήλωσης που έχει ανακληθεί
     */
    public function getStatementID(): ?int
    {
        return $this->get('statementID');
    }

    /**
     * @return string|null ΑΦΜ Υπόχρεης Επιχείρησης
     */
    public function getEntityVatNumber(): ?string
    {
        return $this->get('entityVatNumber');
    }

    /**
     * @return int|null Μοναδικός Κωδικός Καταχώρησης της Ανάκλησης
     */
    public function getRecallId(): ?int
    {
        return $this->get('recallId');
    }

    /**
     * @return int|null Κατάσταση Ανάκλησης
     */
    public function getRecallStatus(): RecallStatus|int|null
    {
        return $this->get('recallStatus');
    }

    /**
     * @return string|null Ημερομηνία Πλήρης Ανάκλησης
     */
    public function getRecallDate(): ?string
    {
        return $this->get('recallDate');
    }

    /**
     * @return string|null Ημερομηνία Ανάκλησης Συναλλαγής
     */
    public function getTransactionDate(): ?string
    {
        return $this->get('transactionDate');
    }

    /**
     * @return string|null ΑΦΜ Ανάκλησης Δήλωσης
     */
    public function getRecallVatNumber(): ?string
    {
        return $this->get('recallVatNumber');
    }
}
