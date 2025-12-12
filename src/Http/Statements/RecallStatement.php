<?php

namespace Firebed\AadeMyData\Http\Statements;

use Firebed\AadeMyData\Enums\RecallStatus;
use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\Statements\StatementResponseDoc;
use Firebed\AadeMyData\Xml\Statements\StatementResponseDocReader;

class RecallStatement extends MyDataRequest
{
    use HasResponseDom;

    /**
     * Αυτή η POST μέθοδος, που είναι διαθέσιμη μόνο για πιστοποιημένους παρόχους, χρησιμοποιείται για την ανάκληση δήλωσης.
     *
     * @param  string  $statementId  Μοναδικός αριθμός δήλωσης προς ανάκληση
     * @param  string  $entityVatNumber  ΑΦΜ Υπόχρεης Οντότητας για την οποία θα γίνει η ανάκληση
     * @param  RecallStatus|int  $recallStatus  Κατάσταση Ανάκλησης
     * @param  string|null  $recallDate  Ημερομηνία Ανάκλησης, συμπληρώνεται από το χρήστη εφόσον το recallStatus = 1 (μορφή: 'YYYY-MM-DD')
     * @return StatementResponseDoc
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 1.0.12
     */
    public function handle(string $statementId, string $entityVatNumber, RecallStatus|int $recallStatus, string $recallDate = null): StatementResponseDoc
    {
        $this->ensureProvider();

        $query = [
            'stmtId' => $statementId,
            'entityVatNumber' => $entityVatNumber,
            'recallStatus' => $recallStatus instanceof RecallStatus ? $recallStatus->value : $recallStatus,
        ];

        if (!empty($recallDate)) {
            $query['recallDate'] = $recallDate;
        }

        // Get the response XML
        $responseXML = $this->post($query);

        // Parse the response XML
        $reader = new StatementResponseDocReader();
        $responseDoc = $reader->parseXML($responseXML);

        $this->responseDom = $reader->getDomDocument();

        return $responseDoc;
    }
}
