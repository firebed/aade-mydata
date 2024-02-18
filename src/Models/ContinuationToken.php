<?php

namespace Firebed\AadeMyData\Models;

class ContinuationToken extends Type
{
    /**
     * @return string|null Παράμετρος για επόμενη κλήση λήψης
     */
    public function getNextPartitionKey(): ?string
    {
        return $this->get('nextPartitionKey');
    }

    /**
     * @return string|null Παράμετρος για επόμενη κλήση λήψης
     */
    public function getNextRowKey(): ?string
    {
        return $this->get('nextRowKey');
    }
}