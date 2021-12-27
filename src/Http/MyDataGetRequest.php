<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\RequestedDoc;

class MyDataGetRequest extends MyDataRequest
{
    /**
     * @param string      $mark             Μοναδικός αριθμός καταχώρησης
     * @param string|null $nextPartitionKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param string|null $nextRowKey       Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     */
    public function handle(string $mark, string $nextPartitionKey = null, string $nextRowKey = null): RequestedDoc
    {
        $query = ['mark' => $mark];
        
        if (!empty($nextPartitionKey)) {
            $query['nextPartitionKey'] = $nextPartitionKey;
        }

        if (!empty($nextRowKey)) {
            $query['nextRowKey'] = $nextRowKey;
        }

        return $this->get($query);
    }
}