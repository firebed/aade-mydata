<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

class Ship extends Type
{
    use HasFactory;
    
    protected array $expectedOrder = [
        'applicationId',
        'applicationDate',
        'doy',
        'shipID'
    ];
    
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
    public function setApplicationId(string $applicationId): static
    {
        return $this->set('applicationId', $applicationId);
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
    public function setApplicationDate(string $applicationDate): static
    {
        return $this->set('applicationDate', $applicationDate);
    }

    /**
     * @return string|null Ημερομηνία ΔΟΥ Δήλωσης
     */
    public function getDoy(): ?string
    {
        return $this->get('doy');
    }

    /**
     * @param  string|null  $doy  Ημερομηνία ΔΟΥ Δήλωσης
     */
    public function setDoy(?string $doy): static
    {
        return $this->set('doy', $doy);
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
    public function setShipID(string $shipID): static
    {
        return $this->set('shipID', $shipID);
    }
}