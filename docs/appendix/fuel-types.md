# Κωδικοί Καυσίμων

| Κωδικός | Enum | Περιγραφή                                                              |
|---------|------|------------------------------------------------------------------------|
| 10 | `FuelCode::BENZINE_95_RON` | Benzine 95RON                                                          |
| 11 | `FuelCode::BENZINE_95_RON_PLUS` | Benzine 95RON+                                                         |
| 12 | `FuelCode::BENZINE_100_RON` | Benzine 100RON                                                         |
| 13 | `FuelCode::BENZINE_LRP` | Benzine LRP                                                            |
| 14 | `FuelCode::AVIATION_GASOLINE` | Βενζίνη αεροπλάνων                                                     |
| 15 | `FuelCode::JET_FUEL` | Ειδικό καύσιμο αεριωθουμένων                                           |
| 20 | `FuelCode::DIESEL` | Diesel                                                                 |
| 21 | `FuelCode::DIESEL_PREMIUM` | Diesel premium                                                         |
| 30 | `FuelCode::DIESEL_HEATING` | Diesel Heating                                                         |
| 31 | `FuelCode::DIESEL_HEATING_PREMIUM` | Diesel Heating premium                                                 |
| 32 | `FuelCode::DIESEL_LIGHT` | Diesel Light                                                           |
| 33 | `FuelCode::DIESEL_OTHER_USES` | Diesel άλλων χρήσεων                                                   |
| 34 | `FuelCode::DIESEL_MARINE` | Diesel ναυτιλίας                                                       |
| 35 | `FuelCode::KEROSENE_JP1` | Κηροζίνη JP1                                                           |
| 36 | `FuelCode::KEROSENE_OTHER_USES` | Κηροζίνη άλλων χρήσεων                                                 |
| 37 | `FuelCode::FUEL_OIL` | Μαζούτ                                                                 |
| 38 | `FuelCode::FUEL_OIL_MARINE` | Μαζούτ ναυτιλίας                                                       |
| 40 | `FuelCode::LPG` | LPG (υγραέριο)                                                         |
| 41 | `FuelCode::LPG_METHANE_ENGINES_BULK` | Υγραέριο (LPG) και μεθάνιο βιομηχανικό /εμπορικό κινητήρων (χύδην)     |
| 42 | `FuelCode::LPG_METHANE_HEATING_BULK` | Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην)        |
| 43 | `FuelCode::LPG_METHANE_ENGINES_BOTTLED` | Υγραέριο (LPG) και μεθάνιο βιομηχανικό /εμπορικό κινητήρων (σε φιάλες) |
| 44 | `FuelCode::LPG_METHANE_HEATING_BOTTLED` | Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες)    |
| 50 | `FuelCode::CNG` | CNG (πεπιεσμένο φυσικό αέριο)                                          |
| 60 | `FuelCode::AROMATIC_HYDROCARBONS_2707` | Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707                    |
| 61 | `FuelCode::CYCLIC_HYDROCARBONS_2902` | Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902                      |
| 70 | `FuelCode::WHITE_SPIRIT` | Ελαφρύ πετρέλαιο (WHITE SPIRIT)                                        |
| 71 | `FuelCode::LIGHT_OILS` | Ελαφριά λάδια                                                          |
| 72 | `FuelCode::BIODIESEL` | Βιοντίζελ                                                              |
| 999 | `FuelCode::OTHER_SERVICE_CHARGES` | Λοιπές χρεώσεις υπηρεσιών                                              |

## Παρατηρήσεις

- Ο κωδικός καυσίμου `999` Χρησιμοποιείται στις περιπτώσεις που σε ένα παραστατικό εκτός από
  καύσιμα υπάρχει η ανάγκη να τιμολογούνται και λοιπές χρεώσεις μικρών ποσών.