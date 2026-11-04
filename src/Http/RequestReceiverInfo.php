<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\ReceiverInfoDoc;
use Firebed\AadeMyData\Xml\ReceiverInfoDocReader;

/**
 * The providers an entity invoices through and the emails at which it accepts invoices
 * from e-invoicing (provider channel only).
 */
class RequestReceiverInfo extends MyDataRequest
{
    use HasResponseDom;

    protected const ?Channel CHANNEL = Channel::Provider;

    /**
     * Με αυτή την μέθοδο ο πάροχος λαμβάνει για μια οντότητα τους ΑΦΜ των παρόχων μέσω
     * των οποίων τιμολογεί και τα email στα οποία έχει δηλώσει ότι λαμβάνει παραστατικά.
     *
     * @param  string  $vatNumber  ΑΦΜ οντότητας
     *
     * @throws UnsupportedChannelException When the connection is not the provider channel
     * @throws MyDataException
     */
    public function handle(string $vatNumber): ReceiverInfoDoc
    {
        return $this->read($this->get(['vatNumber' => $vatNumber]), new ReceiverInfoDocReader);
    }
}
