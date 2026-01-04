<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 1.0.7
 *
 * @deprecated Since version v2.0.0 of myDATA. Do not use.
 */
class TransportDetail extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'vehicleNumber',
    ];

    public function __construct(string|array $vehicleNumber = null)
    {
        if (is_array($vehicleNumber)) {
            parent::__construct($vehicleNumber);
        } else if ($vehicleNumber !== null) {
            parent::__construct(['vehicleNumber' => $vehicleNumber]);
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