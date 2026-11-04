<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Channel;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrDetailsResponse;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\GroupQrDetailsReader;

class RequestGroupQrDetails extends MyDataRequest
{
    use HasResponseDom;

    protected const ?Channel CHANNEL = Channel::Erp;

    protected string $action = 'RequestGroupQRDetails';

    /**
     * 1. Η μέθοδος καλείται για να ανακτηθούν τα επιμέρους QR Codes που περιέχονται σε
     * ένα Ομαδικό QR Code (Group QR).
     * 2. Η απόκριση περιέχει τη λίστα των qrUrls, το πλήθος τους, τον ΑΦΜ του
     * δημιουργού, καθώς και την ημερομηνία λήξης της ομάδας.
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.1
     */
    public function handle(string $groupId): GroupQrDetailsResponse
    {
        return $this->read($this->get(['groupId' => $groupId]), new GroupQrDetailsReader);
    }
}
