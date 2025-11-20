<?php

namespace Firebed\AadeMyData\Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\Statements\RequestedStatementDoc;
use Firebed\AadeMyData\Xml\Statements\RequestedStatementDocReader;

class RequestStatements extends MyDataRequest
{
    use HasResponseDom;

    /**
     * Με αυτή τη μέθοδο ο πιστοποιημένος πάροχος χρήστης λαμβάνει πληροφορίες
     * τις δηλώσεις έκδοσης στοιχείων μέσω Παρόχου ή ΙδιοΠαρόχου που έχει διαβιβάσει.
     *
     * @param  string  $stmtId
     * @param  string|null  $maxstmtId
     * @param  string|null  $entityVatNumber
     * @return RequestedStatementDoc
     * @throws MyDataException
     * @throws MyDataAuthenticationException
     *
     * @version 1.0.12
     */
    public function handle(string $stmtId = '', string $maxstmtId = null, string $entityVatNumber = null): RequestedStatementDoc
    {
        $this->ensureProvider();

        $query = compact('stmtId', 'maxstmtId', 'entityVatNumber');

        $responseXML = $this->get($query);

        $reader = new RequestedStatementDocReader();
        $doc = $reader->parseXML($responseXML);

        $this->responseDom = $reader->getDomDocument();

        return $doc;
    }
}
