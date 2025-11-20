<?php

namespace Firebed\AadeMyData\Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
use Firebed\AadeMyData\Models\Statements\ResponseStatementDoc;
use Firebed\AadeMyData\Models\Statements\Statement;
use Firebed\AadeMyData\Models\Statements\StatementDoc;
use Firebed\AadeMyData\Xml\Statements\ResponseStatementDocReader;
use Firebed\AadeMyData\Xml\Statements\StatementDocWriter;

class SendStatement extends MyDataXmlRequest
{
    /**
     * Η κλήση της μεθόδου SendStatement είναι διαθέσιμη μόνο για πιστοποιημένους παρόχους και αποστέλει
     * μια νέα δήλωση.
     *
     * @param  StatementDoc|Statement  $statement
     * @return ResponseStatementDoc
     * @throws MyDataException
     *
     * @version 1.0.12
     */
    public function handle(StatementDoc|Statement $statement): ResponseStatementDoc
    {
        $this->ensureProvider();

        if ($statement instanceof Statement) {
            $doc = new StatementDoc();
            $doc->set('statement', $statement);
            $statement = $doc;
        }

        return $this->request(new StatementDocWriter(), new ResponseStatementDocReader(), $statement);
    }
}
