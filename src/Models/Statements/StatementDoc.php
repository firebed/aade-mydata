<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\Type;

/**
 * @version 1.0.12
 */
class StatementDoc extends Type
{
    protected array $casts = [
        'statement' => Statement::class,
    ];

    /**
     * @return Statement|null Το αντικείμενο δήλωσης
     */
    public function getStatement(): ?Statement
    {
        return $this->get('statement');
    }

    /**
     * @param Statement $statement Δήλωση Παρόχου/ΙδιοΠαρόχου
     * @return static
     */
    public function setStatement(Statement $statement): static
    {
        return $this->set('statement', $statement);
    }
}
