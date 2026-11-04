<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\Statements;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Channel;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
use Firebed\AadeMyData\Models\Statements\Statement;
use Firebed\AadeMyData\Models\Statements\StatementDoc;
use Firebed\AadeMyData\Models\Statements\StatementResponseDoc;
use Firebed\AadeMyData\Xml\Statements\StatementDocWriter;
use Firebed\AadeMyData\Xml\Statements\StatementResponseDocReader;

class SendStatement extends MyDataXmlRequest
{
    protected const ?Channel CHANNEL = Channel::Provider;

    protected const ?string SCHEMA = 'SendStatement';

    /**
     * Η κλήση της μεθόδου SendStatement είναι διαθέσιμη μόνο για πιστοποιημένους παρόχους και αποστέλει
     * μια νέα δήλωση.
     *
     * @throws MyDataException
     *
     * @version 1.0.12
     */
    public function handle(StatementDoc|Statement $statement): StatementResponseDoc
    {
        if ($statement instanceof Statement) {
            $doc = new StatementDoc;
            $doc->set('statement', $statement);
            $statement = $doc;
        }

        return $this->request(new StatementDocWriter, new StatementResponseDocReader, $statement);
    }
}
