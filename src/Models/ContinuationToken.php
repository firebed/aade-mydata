<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

class ContinuationToken extends Model
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
