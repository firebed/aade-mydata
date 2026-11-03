<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Model;

/**
 * @version 2.0.1
 */
class GroupQrCodeResponse extends Model
{
    /**
     * @return string|null Το αποτέλεσμα της επεξεργασίας.
     *
     * @version 2.0.1
     */
    public function getStatusCode(): ?string
    {
        return $this->get('statusCode');
    }

    /**
     * @return string|null Το νέο, ομαδικό URL του QR Code.
     *
     * @version 2.0.1
     */
    public function getGroupQrUrl(): ?string
    {
        return $this->get('groupQrUrl');
    }

    /**
     * Extract the groupId from the groupQrUrl query parameter.
     *
     * @version 2.0.1
     */
    public function getGroupId(): ?string
    {
        $groupQrUrl = $this->getGroupQrUrl();

        if ($groupQrUrl === null) {
            return null;
        }

        $queryString = parse_url($groupQrUrl, PHP_URL_QUERY);

        if (! is_string($queryString)) {
            return null;
        }

        parse_str($queryString, $queryParams);
        $groupId = $queryParams['groupId'] ?? null;

        return is_string($groupId) ? $groupId : null;
    }

    /**
     * @return int|null Το πλήθος των ΔΑ που περιλαμβάνονται στην ομάδα.
     *
     * @version 2.0.1
     */
    public function getQrUrlsCount(): ?int
    {
        return $this->get('qrUrlsCount');
    }

    /**
     * @return string|null Η ημερομηνία και ώρα λήξης του ομαδικού QR Code.
     *
     * @version 2.0.1
     */
    public function getExpiresAt(): ?string
    {
        return $this->get('expiresAt');
    }
}
