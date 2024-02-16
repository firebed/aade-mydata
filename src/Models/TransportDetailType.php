<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.7
 */
class TransportDetailType extends Type
{
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
     * @param int $vehicleNumber  Μέγιστο επιτρεπτό μήκος 50
     *
     * @version 1.0.7
     */
    public function setVehicleNumber(int $vehicleNumber): void
    {
        $this->set('vehicleNumber', $vehicleNumber);
    }
}