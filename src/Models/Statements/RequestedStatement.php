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
        return ! empty($this->getRecallStatement());
    }

    public function isNotRecalled(): bool
    {
        return empty($this->getRecallStatement());
    }

    public function isAccepted(): bool
    {
        return ! empty($this->getAcceptDate());
    }

    public function isNotAccepted(): bool
    {
        return empty($this->getAcceptDate());
    }

    /**
     * @return RecallStatementDoc|null Ανάκληση δήλωσης
     */
    public function getRecallStatement(): ?RecallStatementDoc
    {
        return $this->get('recallStatement');
    }
}
