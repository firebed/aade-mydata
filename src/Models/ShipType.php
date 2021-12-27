<?php

namespace Firebed\AadeMyData\Models;


class ShipType extends Type
{
    /**
     * @return string Αριθμός Δήλωσης Διενέργειας Δραστηριότητας
     */
    public function getApplicationId(): string
    {
        return $this->get('applicationId');
    }

    /**
     * <h2>Αριθμός Δήλωσης Διενέργειας Δραστηριότητας</h2>
     *
     * @param string $applicationId Αριθμός Δήλωσης Διενέργειας Δραστηριότητας
     */
    public function setApplicationId(string $applicationId): self
    {
        return $this->put('$applicationId', $applicationId);
    }

    /**
     * @return string|null Ημερομηνία Δήλωσης
     */
    public function getApplicationDate(): ?string
    {
        return $this->get('applicationDate');
    }

    /**
     * <h2>Ημερομηνία Δήλωσης</h2>
     *
     * @param string $applicationDate In case of string format is: Y-m-d
     */
    public function setApplicationDate(string $applicationDate): self
    {
        return $this->put('applicationDate', $applicationDate);
    }

    /**
     * @return string|null ΔΟΥ Δήλωσης
     */
    public function getDoy(): ?string
    {
        return $this->get('doy');
    }

    /**
     * <h2>ΔΟΥ Δήλωσης</h2>
     *
     * @param string $doy ΔΟΥ Δήλωσης
     */
    public function setDoy(string $doy): self
    {
        return $this->put('doy', $doy);
    }

    /**
     * @return string Στοιχεία Πλοίου
     */
    public function getShipID(): string
    {
        return $this->get('shipID');
    }

    /**
     * <h2>Στοιχεία Πλοίου</h2>
     *
     * @param string $shipID Στοιχεία Πλοίου
     */
    public function setShipID(string $shipID): self
    {
        return $this->put('shipID', $shipID);
    }


}