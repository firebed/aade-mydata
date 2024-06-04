<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 1.0.7
 */
class TransportDetail extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'vehicleNumber',
    ];

    public function __construct(string $vehicleNumber = null)
    {
        if ($vehicleNumber !== null) {
            $this->setVehicleNumber($vehicleNumber);
        }
    }

    /**
     * @return string Αριθμός Μεταφορικού Μέσου
     *
     * @version 1.0.7
     */
    public function getVehicleNumber(): string
    {
        return $this->get('vehicleNumber');
    }

    /**
     * Αριθμός Μεταφορικού Μέσου
     *
     * @param string $vehicleNumber Μέγιστο επιτρεπτό μήκος 50
     *
     * @version 1.0.7
     */
    public function setVehicleNumber(string $vehicleNumber): static
    {
        return $this->set('vehicleNumber', $vehicleNumber);
    }
}