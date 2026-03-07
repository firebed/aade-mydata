<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class RejectionDetails extends Type
{
    protected array $expectedOrder = [
        'reason',
    ];

    public function __construct(?string $reason = null)
    {
        if ($reason !== null) {
            parent::__construct([
                'reason' => $reason,
            ]);
        }
    }

    /**
     * @return string|null Αιτιολογία απόρριψης
     * @version 2.0.1
     */
    public function getReason(): ?string
    {
        return $this->get('reason');
    }

    /**
     * @param string $reason Αιτιολογία απόρριψης
     * @return RejectionDetails
     * @version 2.0.1
     */
    public function setReason(string $reason): static
    {
        return $this->set('reason', $reason);
    }
}