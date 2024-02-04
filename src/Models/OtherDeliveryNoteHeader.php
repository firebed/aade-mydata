<?php
//benim
namespace Firebed\AadeMyData\Models;

class OtherDeliveryNoteHeader extends Type
{
    /**
     * @return string|null Διεύθυνση Φόρτωσης
     */
    public function getLoadingAddress(): ?string
    {
        return $this->get('loadingAddress');
    }

    /**
     * @param string $loadingAddress Διεύθυνση Φόρτωσης
     */
    public function setLoadingAddress(LoadingAddress|String $loadingAddress): void
    {
        $this->put('loadingAddress', $loadingAddress);
    }
	
    /**
     * @return string|null Διεύθυνση Παράδοσης
     */
    public function getDeliveryAddress(): ?string
    {
        return $this->get('deliveryAddress');
    }

    /**
     * @param string $deliveryAddress Διεύθυνση Παράδοσης
     */
    public function setDeliveryAddress(DeliveryAddress|String $deliveryAddress): void
    {
        $this->put('deliveryAddress', $deliveryAddress);
    }
	
    /**
     * @return string|null Εγκατάσταση έναρξης διακίνησης (Εκδότη)

     */
    public function getStartShippingBranch(): ?string
    {
        return $this->get('startShippingBranch');
    }

    /**
     * @param string $startShippingBranch Εγκατάσταση έναρξης διακίνησης (Εκδότη)
     */
    public function setStartShippingBranch(string $startShippingBranch): void
    {
        $this->put('startShippingBranch', $startShippingBranch);
    }

    /**
     * @return string|null Εγκατάσταση ολοκλήρωσης διακίνησης (Εκδότη)

     */
    public function getCompleteShippingBranch(): ?string
    {
        return $this->get('completeShippingBranch');
    }

    /**
     * @param string $completeShippingBranch Εγκατάσταση ολοκλήρωσης διακίνησης (Εκδότη)
     */
    public function setCompleteShippingBranch(string $completeShippingBranch): void
    {
        $this->put('completeShippingBranch', $completeShippingBranch);
    }	
	
}