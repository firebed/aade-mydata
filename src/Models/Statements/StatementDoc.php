<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\Model;

/**
 * @version 1.0.12
 */
class StatementDoc extends Model
{
    protected function casts(): array
    {
        return [
            'statement' => Statement::class,
        ];
    }

    /**
     * @return Statement|null Το αντικείμενο δήλωσης
     */
    public function getStatement(): ?Statement
    {
        return $this->get('statement');
    }

    /**
     * @param  Statement  $statement  Δήλωση Παρόχου/ΙδιοΠαρόχου
     */
    public function setStatement(Statement $statement): static
    {
        return $this->set('statement', $statement);
    }
}
