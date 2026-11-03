# Ειδική Κατηγορία Παραστατικού

| Κωδικός | Enum | Περιγραφή                                                                                             |
|---------|------|-------------------------------------------------------------------------------------------------------|
| 1 | `SpecialInvoiceCategory::SUBSIDIES_GRANTS` | Επιδοτήσεις – Επιχορηγήσεις                                                                           |
| 2 | `SpecialInvoiceCategory::HOTEL_RETAIL_INCOME` | Έσοδα Λιανικής Ξενοδοχείων ~~– Χρεώσεις Δωματίου~~                                                    |
| 3 | `SpecialInvoiceCategory::ACCOUNTING_ENTRY` | Λογιστική Εγγραφή                                                                                     |
| 4 | `SpecialInvoiceCategory::TAX_FREE` | Tax Free                                                                                              |
| 5 | `SpecialInvoiceCategory::COMPLEX_DOMESTIC_FOREIGN_TRANSACTIONS` | Σύνθετες συναλλαγές ημεδαπής – αλλοδαπής                                                              |
| 6 | `SpecialInvoiceCategory::DECISION_139818_BENEFICIARIES` | Δικαιούχοι του άρθρου 3 της υπό στοιχεία 139818 ΕΞ2022/28.09.2022 (Β’5083) κοινής υπουργικής απόφασης |
| 7 | `SpecialInvoiceCategory::AGRICULTURAL_PURCHASES_ARTICLE_41` | Αγορά αγροτικών αγαθών υπηρεσιών Άρθρο 41 του Κώδικα ΦΠΑ                                              | 
| 8 | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_AADE_1` | Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_1                                                                             |
| 9 | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_AADE_2` | Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_2                                                                             |
| 10 | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_DEVIATION` | Έσοδα Λιανικών ΦΗΜ Επιχείρησης Απόκλιση                                                               |
| 11 | `SpecialInvoiceCategory::HEATING_ALLOWANCE` | Επίδομα Θέρμανσης                                                                                     |
| 12 | `SpecialInvoiceCategory::RESTAURANT_TRANSACTIONS` | Συναλλαγές εστίασης                                                                                   |
| 13 | `SpecialInvoiceCategory::CORRELATION_DIFFICULTY` | Ένδειξη Δυσχέρεια Συσχέτισης                                                                          |

## Παρατηρήσεις

- `TaxFree` Έγκυρη τιμή μόνο για διαβίβαση μέσω erp ή έκδοση μέσω παρόχου ή timologio.
- `Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_1` Μόνο για ανάγνωση – μή έγκυρη τιμή για αποστολή μέσω ERP / Πάροχο.
- `Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_2` Μόνο για ανάγνωση – μή έγκυρη τιμή για αποστολή μέσω ERP / Πάροχο.
- `Ένδειξη Δυσχέρεια Συσχέτισης` Έγκυρη τιμή μόνο για τύπους παραστατικών 11.4 και 14.30