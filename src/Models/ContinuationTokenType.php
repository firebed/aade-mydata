<?php

namespace Firebed\AadeMyData\Models;


class ContinuationTokenType extends Type
{    
    public function getNextPartitionKey(): string
    {
        return $this->get('nextPartitionKey');
    }
    
    public function setNextPartitionKey(string $nextPartitionKey): self
    {
        return $this->put('nextPartitionKey', $nextPartitionKey);
    }
    
    public function getNextRowKey(): string
    {
        return $this->get('nextPartitionKey');
    }

    public function setNextRowKey(string $nextRowKey): self
    {
        return $this->put('nextRowKey', $nextRowKey);
    }
}