<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 1.0.8
 */
class OtherDeliveryNoteHeader extends Type
{
    use HasFactory;
    
    protected array $expectedOrder = [
        'loadingAddress',
        'deliveryAddress',
        'startShippingBranch',
        'completeShippingBranch'
    ];
    
    /**
     * @return Address|null Διεύθυνση Φόρτωσης
     * @version 1.0.8
     */
    public function getLoadingAddress(): ?Address
    {
        return $this->get('loadingAddress');
    }

    /**
     * Συμπληρώνεται για παραστατικά που είναι δελτία αποστολής
     * (π.χ 9.3) ή τιμολόγιο και δελτίο αποστολής (isDeliveryNote = true).
     *
     * @param Address $loadingAddress Διεύθυνση Φόρτωσης
     * @version 1.0.8
     */
    public function setLoadingAddress(Address $loadingAddress): void
    {
        $this->set('loadingAddress', $loadingAddress);
    }

    /**
     * @return Address|null Διεύθυνση Παράδοσης
     * @version 1.0.8
     */
    public function getDeliveryAddress(): ?Address
    {
        return $this->get('deliveryAddress');
    }

    /**
     * Συμπληρώνεται για παραστατικά που είναι δελτία αποστολής
     * (π.χ 9.3) ή τιμολόγιο και δελτίο αποστολής (isDeliveryNote = true).
     *
     * @param Address $deliveryAddress Διεύθυνση Παράδοσης
     * @version 1.0.8
     */
    public function setDeliveryAddress(Address $deliveryAddress): void
    {
        $this->set('deliveryAddress', $deliveryAddress);
    }

    /**
     * @return int|null Εγκατάσταση έναρξης διακίνησης (Εκδότη)
     * @version 1.0.8
     */
    public function getStartShippingBranch(): ?int
    {
        return $this->get('startShippingBranch');
    }

    /**
     * <ul>
     * <li>Συμπληρώνεται για παραστατικά που είναι δελτία αποστολής
     * (π.χ 9.3) ή τιμολόγιο και δελτίο αποστολής (isDeliveryNote = true).</li>
     *
     * <li>Με το πεδίο startShippingBranch ορίζεται το υποκατάστημα από το οποίο έγινε η
     * έναρξη της διακίνησης, σε περίπτωση που η έναρξη της διακίνησης γίνεται από
     * κάποιο υποκατάστημα (εγκατάσταση) του εκδότη του παραστατικού, το οποίο είναι
     * διαφορετικό από το υποκατάστημα του εκδότη του δελτίου.</li>
     * </ul>
     *
     * @param int|null $startShippingBranch Εγκατάσταση έναρξης διακίνησης (Εκδότη)
     * @version 1.0.8
     */
    public function setStartShippingBranch(?int $startShippingBranch): void
    {
        $this->set('startShippingBranch', $startShippingBranch);
    }

    /**
     * @return int|null Εγκατάσταση ολοκλήρωσης διακίνησης (Λήπτη)
     * @version 1.0.8
     */
    public function getCompleteShippingBranch(): ?int
    {
        return $this->get('completeShippingBranch');
    }

    /**
     * <ul>
     * <li>Συμπληρώνεται για παραστατικά που είναι δελτία αποστολής
     * (π.χ 9.3) ή τιμολόγιο και δελτίο αποστολής (isDeliveryNote = true).</li>
     *
     * <li>Με το πεδίο completeShippingBranch ορίζεται το υποκατάστημα στο οποίο θα
     * ολοκληρωθεί η διακίνηση, σε περίπτωση που η διακίνηση θα ολοκληρωθεί σε
     * κάποιο υποκατάστημα (εγκατάσταση) του λήπτη του παραστατικού, το οποίο είναι
     * διαφορετικό από το υποκατάστημα του λήπτη του δελτίου</li>
     * </ul>
     *
     * @param int|null $completeShippingBranch Εγκατάσταση ολοκλήρωσης διακίνησης (Λήπτη)
     * @version 1.0.8
     */
    public function setCompleteShippingBranch(?int $completeShippingBranch): void
    {
        $this->set('completeShippingBranch', $completeShippingBranch);
    }

}