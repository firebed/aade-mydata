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
     * @param string $nextPartitionKey Παράμετρος για επόμενη κλήση λήψης
     */
    public function setNextPartitionKey(string $nextPartitionKey): void
    {
        $this->put('nextPartitionKey', $nextPartitionKey);
    }

    /**
     * @return string|null Παράμετρος για επόμενη κλήση λήψης
     */
    public function getNextRowKey(): ?string
    {
        return $this->get('nextRowKey');
    }

    /**
     * @param string $nextRowKey Παράμετρος για επόμενη κλήση λήψης
     */
    public function setNextRowKey(string $nextRowKey): void
    {
        $this->put('nextRowKey', $nextRowKey);
    }    

}