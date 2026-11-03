# Σκοπός Διακίνησης

| Κωδικός | Enum | Περιγραφή                                      |
|---------|------|------------------------------------------------|
| 1 | `MovePurpose::SALE` | Πώληση                                         |
| 2 | `MovePurpose::SALE_FOR_THIRD_PARTIES` | Πώληση για Λογαριασμό Τρίτων                   |
| 3 | `MovePurpose::SAMPLING` | Δειγματισμός                                   |
| 4 | `MovePurpose::EXHIBITION` | Έκθεση                                         |
| 5 | `MovePurpose::RETURN` | Επιστροφή                                      |
| 6 | `MovePurpose::SAFEKEEPING` | Φύλαξη                                         |
| 7 | `MovePurpose::PROCESSING_ASSEMBLY_DISASSEMBLY` | Επεξεργασία - Συναρμολόγηση - Αποσυναρμολόγηση |
| 8 | `MovePurpose::INTERNAL_MOVEMENT` | Ενδοδιακίνηση                                  |
| 9 | `MovePurpose::PURCHASE` | Αγορά                                          |
| 10 | `MovePurpose::SHIP_AND_AIRCRAFT_SUPPLY` | Εφοδιασμός πλοίων και αεροσκαφών               |
| 11 | `MovePurpose::FREE_DISPOSAL` | Δωρεάν διάθεση                                 |
| 12 | `MovePurpose::WARRANTY` | Εγγύηση                                        |
| 13 | `MovePurpose::LOAN_FOR_USE` | Χρησιδανεισμός                                 |
| 14 | `MovePurpose::STORAGE_AT_THIRD_PARTIES` | Αποθήκευση σε Τρίτους                          |
| 15 | `MovePurpose::RETURN_FROM_SAFEKEEPING` | Επιστροφή από Φύλαξη                           |
| 16 | `MovePurpose::RECYCLING` | Ανακύκλωση                                     |
| 17 | `MovePurpose::SCRAP_DESTRUCTION` | Καταστροφή άχρηστου υλικού                     |
| 18 | `MovePurpose::FIXED_ASSETS_MOVEMENT` | Διακίνηση Παγίων (Ενδοδιακίνηση)               |
| 19 | `MovePurpose::OTHER_MOVEMENTS` | Λοιπές Διακινήσεις                             |
| 20 | `MovePurpose::TRANSPORT_AND_COURIER` | Μεταφορές - Ταχυμεταφορές                      |

> [!NOTE]
> Από την έκδοση 1.0.11 και μετά δε δίνεται η δυνατότητα αποστολής των σκοπών διακίνησης με α/α 6, 15, 16, 17 και 18 