<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.10
 */
class E3Info extends Type
{
    public function getVat(): ?string
    {
        return $this->get('V_Afm');
    }

    /**
     * @return string|null
     * @version 1.0.10
     */
    public function getMark(): ?string
    {
        return $this->get('V_Mark');
    }

    /**
     * @return string|null
     * @version 1.0.10
     */
    public function getIssueDate(): ?string
    {
        return $this->get('IssueDate');
    }

    /**
     * @return string|null
     * @version 1.0.10
     */
    public function getClassCategory(): ?string
    {
        return $this->get('V_Class_Category');
    }

    /**
     * @return string|null
     * @version 1.0.10
     */
    public function getClassType(): ?string
    {
        return $this->get('V_Class_Type');
    }

    /**
     * @return string|null
     * @version 1.0.10
     */
    public function getClassValue(): ?string
    {
        return $this->get('V_Class_Value');
    }
}