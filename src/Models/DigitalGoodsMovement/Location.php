<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class Location extends Type
{
    protected array $expectedOrder = [
        'longitude',
        'latitude',
    ];

    public function __construct(?float $longitude = null, ?float $latitude = null)
    {
        if ($longitude !== null || $latitude !== null) {
            parent::__construct([
                'longitude' => $longitude,
                'latitude' => $latitude,
            ]);
        }
    }

    /**
     * @return float|null Γεωγραφικό μήκος
     * @version v2.0.1
     */
    public function getLongitude(): ?float
    {
        return $this->get('longitude');
    }

    /**
     * @param float $longitude Γεωγραφικό μήκος
     * @return $this
     * @version v2.0.1
     */
    public function setLongitude(float $longitude): static
    {
        return $this->set('longitude', $longitude);
    }

    /**
     * @return float|null Γεωγραφικό πλάτος
     * @version v2.0.1
     */
    public function getLatitude(): ?float
    {
        return $this->get('latitude');
    }

    /**
     * @param float $latitude Γεωγραφικό πλάτος
     * @return $this
     * @version v2.0.1
     */
    public function setLatitude(float $latitude): static
    {
        return $this->set('latitude', $latitude);
    }
}