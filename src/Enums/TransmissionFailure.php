<?php

namespace Firebed\AadeMyData\Enums;

enum TransmissionFailure: int
{
    /**
     * Στην περίπτωση αδυναμίας επικοινωνίας οντότητας με τον πάροχο κατά την
     * έκδοση/διαβίβαση παραστατικού.
     */
    case ENTITY_CONNECTION_FAILURE = 1;
    
    /**
     * Στην περίπτωση αδυναμίας επικοινωνίας του παρόχου με 
     * το myDATA κατά την έκδοση/διαβίβαση παραστατικού.
     */
    case MYDATA_CONNECTION_FAILURE = 2;

    /**
     * Απώλεια διασύνδεσης. Είναι επιτρεπτή μόνο για περίπτωση αποστολής από ERP
     */
    case CONNECTION_LOSS = 3;
}
