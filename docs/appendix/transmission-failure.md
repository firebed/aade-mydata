# Transmission failure codes

Why the invoice was not transmitted in time (`transmissionFailure`). Enum: `TransmissionFailure`.

```php
use Firebed\AadeMyData\Enums\TransmissionFailure;
use Firebed\AadeMyData\Models\Invoice;

$invoice = (new Invoice())->setTransmissionFailure(TransmissionFailure::CONNECTION_LOSS);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `TransmissionFailure::ERP_CONNECTION_FAILURE` | Στην περίπτωση αδυναμίας επικοινωνίας οντότητας με τον πάροχο κατά την έκδοση/διαβίβαση παραστατικού. | The entity cannot reach the provider while issuing/transmitting the invoice. |
| 2 | `TransmissionFailure::MYDATA_CONNECTION_FAILURE` | Στην περίπτωση αδυναμίας επικοινωνίας του παρόχου με το myDATA κατά την έκδοση/διαβίβαση παραστατικού. | The provider cannot reach myDATA while issuing/transmitting the invoice. |
| 3 | `TransmissionFailure::CONNECTION_LOSS` | Απώλεια διασύνδεσης. Είναι επιτρεπτή μόνο για περίπτωση αποστολής από ERP | Connection loss. Allowed only when sending from an ERP. |
| 4 | `TransmissionFailure::UTILITY_SUPPLIER_CONNECTION_FAILURE` | Στην περίπτωση παραστατικών οντοτήτων της περ. γ’ της παρ. 2 του άρθρου 5 του ν. 1138/2020 (οντότητες παροχής ηλεκτρικής ενέργειας και φυσικού αερίου (Δ.Ε.Η. και λοιποί πάροχοι), η Ε.Υ.Δ.Α.Π, κλπ…) | Invoices of entities under case c, par. 2, art. 5 of L. 1138/2020 (electricity and natural gas suppliers such as DEI, EYDAP, etc.) |

- `1` and `2`: provider channel only.
- `3`: ERP channel only.
