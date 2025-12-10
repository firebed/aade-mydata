<?php

namespace Firebed\AadeMyData\Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
use Firebed\AadeMyData\Models\Statements\StatementResponseDoc;
use Firebed\AadeMyData\Models\Statements\Statement;
use Firebed\AadeMyData\Models\Statements\StatementDoc;
use Firebed\AadeMyData\Xml\Statements\StatementResponseDocReader;
use Firebed\AadeMyData\Xml\Statements\StatementDocWriter;

class SendStatement extends MyDataXmlRequest
{
    /**
     * Η κλήση της μεθόδου SendStatement είναι διαθέσιμη μόνο για πιστοποιημένους παρόχους και αποστέλει
     * μια νέα δήλωση.
     *
     * @param  StatementDoc|Statement  $statement
     * @return StatementResponseDoc
     * @throws MyDataException
     *
     * @version 1.0.12
     */
    public function handle(StatementDoc|Statement $statement): StatementResponseDoc
    {
        $this->ensureProvider();

        if ($statement instanceof Statement) {
            $doc = new StatementDoc();
            $doc->set('statement', $statement);
            $statement = $doc;
        }

        return $this->request(new StatementDocWriter(), new StatementResponseDocReader(), $statement);
    }
}
