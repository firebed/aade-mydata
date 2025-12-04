<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\Type;

/**
 * @version 1.0.12
 */
class RequestedStatement extends Type
{

    protected array $casts = [
        'statement' => Statement::class,
        'recallStatement' => RecallStatementDoc::class,
    ];

    protected array $expectedOrder = [
        'statement',
        'acceptVatNumber',
        'acceptDate',
        'recallStatement',
    ];

    /**
     * Δεδομένα δήλωσης έκδοσης στοιχείων μέσω Παρόχου ή ΙδιοΠαρόχου
     *
     * @return Statement|null Στοιχείο δήλωσης
     */
    public function getStatement(): ?Statement
    {
        return $this->get('statement');
    }

    /**
     * @return string|null ΑΦΜ αποδοχής δήλωσης
     */
    public function getAcceptVatNumber(): ?string
    {
        return $this->get('acceptVatNumber');
    }

    /**
     * @return string|null Ημερομηνία αποδοχής δήλωσης
     */
    public function getAcceptDate(): ?string
    {
        return $this->get('acceptDate');
    }

    public function isRecalled(): bool
    {
        return !is_null($this->getRecallStatement()) && count($this->getRecallStatement()) > 0;
    }

    /**
     * @return RecallStatementDoc|null Ανάκληση δήλωσης
     */
    public function getRecallStatement(): ?RecallStatementDoc
    {
        return $this->get('recallStatement');
    }
}
