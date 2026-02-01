<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class GroupQrDetailsResponse extends Type
{
    protected array $casts = [
        'qrUrls' => QrUrls::class,
    ];

    /**
     * @return string|null Το αναγνωριστικό του Ομαδικού QR Code
     * @version 2.0.1
     */
    public function getGroupId(): ?string
    {
        return $this->get('groupId');
    }

    /**
     * @return QrUrls|null Λίστα με τα URLs των QR Codes
     * @version 2.0.1
     */
    public function getQrUrls(): ?QrUrls
    {
        return $this->get('qrUrls');
    }

    /**
     * @return int|null Αριθμός των URLs των QR Codes
     * @version 2.0.1
     */
    public function getQrUrlsCount(): ?int
    {
        return $this->getQrUrls()?->count();
    }

    /**
     * @return string|null ΑΦΜ της οντότητας που δημιούργησε το Ομαδικό QR Code
     * @version 2.0.1
     */
    public function getGroupQrCreatorVatNumber(): ?string
    {
        return $this->get('groupQrCreatorVatNumber');
    }

    /**
     * @return string|null Ημερομηνία και ώρα δημιουργίας του Ομαδικού QR Code
     * @version 2.0.1
     */
    public function getCreatedAt(): ?string
    {
        return $this->get('createdAt');
    }

    /**
     * @return string|null Ημερομηνία και ώρα λήξης ισχύος του Ομαδικού QR Code
     * @version 2.0.1
     */
    public function getExpiresAt(): ?string
    {
        return $this->get('expiresAt');
    }

    /**
     * @return string|null Κωδικός Αποτελέσματος
     * @version 2.0.1
     */
    public function getStatusCode(): ?string
    {
        return $this->get('statusCode');
    }

    public function getMessage(): ?string
    {
        return $this->get('message');
    }
}