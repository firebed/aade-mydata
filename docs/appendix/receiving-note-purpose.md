# Αιτία Έκδοσης Δελτίου Ποσοτικής Παραλαβής <sub><sup>v2.0.2</sup></sub>

Αφορά τα παραστατικά τύπου `10.1` και `10.2` (Δελτίο Ποσοτικής Παραλαβής).

| Κωδικός | Enum | Περιγραφή                                | Αποδεκτό για |
|---------|------|------------------------------------------|--------------|
| 1 | `ReceivingNotePurpose::NOT_OBLIGED_TO_ISSUE` | ΜΗ ΥΠΟΧΡΕΟΣ ΕΚΔΟΣΗΣ                      | 10.1 & 10.2  |
| 2 | `ReceivingNotePurpose::REFUSAL_OR_INADVERTENT_NON_ISSUANCE` | ΑΡΝΗΣΗ ΕΚΔΟΣΗΣ / ΕΚ ΠΑΡΑΔΡΟΜΗΣ ΜΗ ΕΚΔΟΣΗ | 10.1 & 10.2  |
| 3 | `ReceivingNotePurpose::INTRA_COMMUNITY_ACQUISITION` | ΕΝΔΟΚΟΙΝΟΤΙΚΗ ΑΠΟΚΤΗΣΗ                   | 10.1 & 10.2  |
| 4 | `ReceivingNotePurpose::THIRD_COUNTRY_ACQUISITION` | ΑΠΟΚΤΗΣΗ ΤΡΙΤΗ ΧΩΡΑ                      | 10.1 & 10.2  |
| 5 | `ReceivingNotePurpose::QUANTITATIVE_CONTROL` | ΠΟΣΟΤΙΚΟΣ ΕΛΕΓΧΟΣ                        | 10.1         |
| 6 | `ReceivingNotePurpose::NON_OR_PARTIAL_DELIVERY` | ΜΗ / ΜΕΡΙΚΗ ΠΑΡΑΔΟΣΗ                     | 10.1 & 10.2  |
| 7 | `ReceivingNotePurpose::OTHER_CASES` | ΛΟΙΠΕΣ ΠΕΡΙΠΤΩΣΕΙΣ                       | 10.1 & 10.2  |

> [!NOTE]
> Όταν επιλεγεί ο κωδικός `7` (ΛΟΙΠΕΣ ΠΕΡΙΠΤΩΣΕΙΣ) πρέπει να συμπληρωθεί και το
> πεδίο `otherReceivingNotePurposeTitle` (max 150 χαρακτήρες).
