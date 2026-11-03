<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.10
 */
class E3Info extends Model
{
    public function getVat(): ?string
    {
        return $this->get('V_Afm');
    }

    /**
     * @version 1.0.10
     */
    public function getMark(): ?string
    {
        return $this->get('V_Mark');
    }

    /**
     * @version 1.0.10
     */
    public function getIssueDate(): ?string
    {
        return $this->get('IssueDate');
    }

    /**
     * @version 1.0.10
     */
    public function getClassCategory(): ?string
    {
        return $this->get('V_Class_Category');
    }

    /**
     * @version 1.0.10
     */
    public function getClassType(): ?string
    {
        return $this->get('V_Class_Type');
    }

    /**
     * @version 1.0.10
     */
    public function getClassValue(): ?string
    {
        return $this->get('V_Class_Value');
    }
}
