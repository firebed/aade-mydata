<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Models\RequestedDoc;
use GuzzleHttp\Exception\GuzzleException;

class MyDataGetRequest extends MyDataRequest
{
    /**
     * @param string      $mark             Μοναδικός αριθμός καταχώρησης
     * @param string|null $entityVatNumber  ΑΦΜ οντότητας
     * @param string|null $nextPartitionKey Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param string|null $nextRowKey       Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @throws GuzzleException
     */
    public function handle(string $mark = '', string $entityVatNumber = null, string $nextPartitionKey = null, string $nextRowKey = null): RequestedDoc
    {
        $query = compact('mark');

        if (!empty($entityVatNumber)) {
            $query['entityVatNumber'] = $entityVatNumber;
        }
        
        if (!empty($nextPartitionKey)) {
            $query['nextPartitionKey'] = $nextPartitionKey;
        }

        if (!empty($nextRowKey)) {
            $query['nextRowKey'] = $nextRowKey;
        }

        return $this->get($query);
    }
}