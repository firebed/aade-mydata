<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;
use Firebed\AadeMyData\Models\Type;

/**
 * @version 2.0.1
 */
class TransportDetails extends Type
{
    protected array $expectedOrder = [
        'vehicleNumber',
        'transportType',
        'timeStamp',
        'carrierVatNumber',
        'pNumber',
        'location',
    ];

    protected array $casts = [
        'transportType' => TransportType::class,
        'location' => Location::class,
    ];

    /**
     * @return string|null Αριθμός Μεταφορικού Μέσου (Αριθμός κυκλοφορίας/Όνομα πλωτού μέσου/Κωδικός Δρομολογίου ή πτήσης/Διακίνηση άνευ Μεταφορικού Μέσου)
     * @version 2.0.1
     */
    public function getVehicleNumber(): ?string
    {
        return $this->get('vehicleNumber');
    }

    /**
     * @param string $vehicleNumber Αριθμός Μεταφορικού Μέσου (Αριθμός κυκλοφορίας/Όνομα πλωτού μέσου/Κωδικός Δρομολογίου ή πτήσης/Διακίνηση άνευ Μεταφορικού Μέσου)
     * @return static
     * @version 2.0.1
     */
    public function setVehicleNumber(string $vehicleNumber): static
    {
        return $this->set('vehicleNumber', $vehicleNumber);
    }

    /**
     * @return TransportType|null Είδος Μεταφορικού Μέσου
     * @version 2.0.1
     */
    public function getTransportType(): ?TransportType
    {
        return $this->get('transportType');
    }

    /**
     * @param TransportType|int $transportType Είδος Μεταφορικού Μέσου
     * @return static
     * @version 2.0.1
     */
    public function setTransportType(TransportType|int $transportType): static
    {
        return $this->set('transportType', $transportType);
    }

    /**
     * @return string|null Χρονοσήμανση (Y-m-dTH:i:s). Συμπληρώνεται από την υπηρεσία.
     * @version 2.0.1
     */
    public function getTimestamp(): ?string
    {
        return $this->get('timeStamp');
    }

    /**
     * @return string|null ΑΦΜ Μεταφορικής Εταιρείας
     * @version 2.0.1
     */
    public function getCarrierVatNumber(): ?string
    {
        return $this->get('carrierVatNumber');
    }

    /**
     * @param string $carrierVatNumber ΑΦΜ Μεταφορικής Εταιρείας
     * @return static
     * @version 2.0.1
     */
    public function setCarrierVatNumber(string $carrierVatNumber): static
    {
        return $this->set('carrierVatNumber', $carrierVatNumber);
    }

    /**
     * @return string|null Αριθμός κυκλοφορίας "Ρ" (αριθμός κυκλοφορίας του επικαθήμενου/ρυμουλκούμενου οχήματος)
     * @version 2.0.1
     */
    public function getTrailorNumber(): ?string
    {
        return $this->get('pNumber');
    }

    /**
     * @param string|null $pNumber Αριθμός κυκλοφορίας "Ρ" (αριθμός κυκλοφορίας του επικαθήμενου/ρυμουλκούμενου οχήματος)
     * @return static
     * @version 2.0.1
     */
    public function setTrailorNumber(?string $pNumber): static
    {
        return $this->set('pNumber', $pNumber);
    }

    /**
     * @return Location|null Τοποθεσία Μεταφόρτωσης
     * @version 2.0.1
     */
    public function getLocation(): ?Location
    {
        return $this->get('location');
    }

    /**
     * @param Location $location Τοποθεσία Μεταφόρτωσης
     * @return static
     * @version 2.0.1
     */
    public function setLocation(Location $location): static
    {
        return $this->set('location', $location);
    }
}