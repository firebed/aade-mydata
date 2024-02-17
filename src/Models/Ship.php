<?php

namespace Firebed\AadeMyData\Models;

class Ship extends Type
{
    /**
     * @return string|null Αριθμός Δήλωσης Διενέργειας Δραστηριότητας
     */
    public function getApplicationId(): ?string
    {
        return $this->get('applicationId');
    }

    /**
     * @param string $applicationId Αριθμός Δήλωσης Διενέργειας Δραστηριότητας
     */
    public function setApplicationId(string $applicationId): void
    {
        $this->set('applicationId', $applicationId);
    }

    /**
     * @return string|null Ημερομηνία Δήλωσης Y-m-d
     */
    public function getApplicationDate(): ?string
    {
        return $this->get('applicationDate');
    }

    /**
     * @param string $applicationDate Ημερομηνία Δήλωσης Y-m-d
     */
    public function setApplicationDate(string $applicationDate): void
    {
        $this->set('applicationDate', $applicationDate);
    }

    /**
     * @return string|null Ημερομηνία ΔΟΥ Δήλωσης
     */
    public function getDoy(): ?string
    {
        return $this->get('doy');
    }

    /**
     * @param string $doy Ημερομηνία ΔΟΥ Δήλωσης
     */
    public function setDoy(string $doy): void
    {
        $this->set('doy', $doy);
    }

    /**
     * @return string|null Στοιχεία Πλοίου
     */
    public function getShipID(): ?string
    {
        return $this->get('shipID');
    }

    /**
     * @param string $shipID Στοιχεία Πλοίου
     */
    public function setShipID(string $shipID): void
    {
        $this->set('shipID', $shipID);
    }
}