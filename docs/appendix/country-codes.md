# Country codes

Country of the issuer or counterpart. Enum: `CountryCode`. The values are [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) codes, and the case name equals the code.

```php
use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Models\Counterpart;

$counterpart = (new Counterpart())
    ->setVatNumber('123456789')
    ->setCountry(CountryCode::DE)
    ->setBranch(0);

CountryCode::DE->isInEuropeanUnion(); // true
CountryCode::europeanUnionCountries(); // the 27 EU member states
```

`Party::setCountryDocumentId()` takes the same enum.

| Code | `label()` | English |
|---|---|---|
| AD | Ανδόρα | Andorra |
| AE | Ηνωμένα Αραβικά Εμιράτα | United Arab Emirates |
| AF | Αφγανιστάν | Afghanistan |
| AG | Αντίγκουα και Μπαρμπούντα | Antigua and Barbuda |
| AI | Ανγκουίλα | Anguilla |
| AL | Αλβανία | Albania |
| AM | Αρμενία | Armenia |
| AN | Ολλανδικές Αντίλλες | Netherlands Antilles |
| AO | Αγκόλα | Angola |
| AQ | Ανταρκτική | Antarctica |
| AR | Αργεντινή | Argentina |
| AS | Αμερικανική Σαμόα | American Samoa |
| AT | Αυστρία | Austria |
| AU | Αυστραλία | Australia |
| AW | Αρούμπα | Aruba |
| AX | Νήσοι Όλαντ | Åland Islands |
| AZ | Αζερμπαϊτζάν | Azerbaijan |
| BA | Βοσνία και Ερζεγοβίνη | Bosnia and Herzegovina |
| BB | Μπαρμπάντος | Barbados |
| BD | Μπαγκλαντές | Bangladesh |
| BE | Βέλγιο | Belgium |
| BF | Μπουρκίνα Φάσο | Burkina Faso |
| BG | Βουλγαρία | Bulgaria |
| BH | Μπαχρέιν | Bahrain |
| BI | Μπουρούντι | Burundi |
| BJ | Μπενίν | Benin |
| BL | Άγιος Βαρθολομαίος | Saint Barthélemy |
| BM | Βερμούδες | Bermuda |
| BN | Μπρουνέι | Brunei Darussalam |
| BO | Βολιβία | Bolivia |
| BR | Βραζιλία | Brazil |
| BS | Μπαχάμες | Bahamas |
| BT | Μπουτάν | Bhutan |
| BV | Νήσος Μπουβέ | Bouvet Island |
| BW | Μποτσουάνα | Botswana |
| BY | Λευκορωσία | Belarus |
| BZ | Μπελίζ | Belize |
| CA | Καναδάς | Canada |
| CC | Νήσοι Κόκος (Κίλινγκ) | Cocos (Keeling) Islands |
| CD | Κονγκό (Λαϊκή Δημοκρατία του) | Congo, Democratic Republic of the |
| CF | Κεντροαφρικανική Δημοκρατία | Central African Republic |
| CG | Κονγκό | Congo |
| CH | Ελβετία | Switzerland |
| CI | Ακτή Ελεφαντοστού | Côte d'Ivoire |
| CK | Νήσοι Κουκ | Cook Islands |
| CL | Χιλή | Chile |
| CM | Καμερούν | Cameroon |
| CN | Κίνα | China |
| CO | Κολομβία | Colombia |
| CR | Κόστα Ρίκα | Costa Rica |
| CU | Κούβα | Cuba |
| CV | Πράσινο Ακρωτήριο | Cape Verde |
| CX | Νήσος των Χριστουγέννων | Christmas Island |
| CY | Κύπρος | Cyprus |
| CZ | Τσεχία | Czech Republic |
| DE | Γερμανία | Germany |
| DJ | Τζιμπουτί | Djibouti |
| DK | Δανία | Denmark |
| DM | Ντομίνικα | Dominica |
| DO | Δομινικανή Δημοκρατία | Dominican Republic |
| DZ | Αλγερία | Algeria |
| EC | Εκουαδόρ | Ecuador |
| EE | Εσθονία | Estonia |
| EG | Αίγυπτος | Egypt |
| EH | Δυτική Σαχάρα | Western Sahara |
| ER | Ερυθραία | Eritrea |
| ES | Ισπανία | Spain |
| ET | Αιθιοπία | Ethiopia |
| FI | Φινλανδία | Finland |
| FJ | Φίτζι | Fiji |
| FK | Νήσοι Φώκλαντ | Falkland Islands (Malvinas) |
| FM | Μικρονησία | Micronesia, Federated States of |
| FO | Νήσοι Φερόες | Faroe Islands |
| FR | Γαλλία | France |
| GA | Γκαμπόν | Gabon |
| GB | Ηνωμένο Βασίλειο | United Kingdom |
| GD | Γρενάδα | Grenada |
| GE | Γεωργία | Georgia |
| GF | Γαλλική Γουιάνα | French Guiana |
| GG | Γκέρνσεϊ | Guernsey |
| GH | Γκάνα | Ghana |
| GI | Γιβραλτάρ | Gibraltar |
| GL | Γροιλανδία | Greenland |
| GM | Γκάμπια | Gambia |
| GN | Γουινέα | Guinea |
| GP | Γουαδελούπη | Guadeloupe |
| GQ | Ισημερινή Γουινέα | Equatorial Guinea |
| GR | Ελλάδα | Greece |
| GS | Νότια Γεωργία και Νότιες Νήσοι Σάντουιτς | South Georgia and the South Sandwich Islands |
| GT | Γουατεμάλα | Guatemala |
| GU | Γκουάμ | Guam |
| GW | Γουινέα-Μπισάου | Guinea-Bissau |
| GY | Γουιάνα | Guyana |
| HK | Χονγκ Κονγκ | Hong Kong |
| HM | Νήσος Χερντ και Νήσοι Μακντόναλντ | Heard Island and McDonald Islands |
| HN | Ονδούρα | Honduras |
| HR | Κροατία | Croatia |
| HT | Αϊτή | Haiti |
| HU | Ουγγαρία | Hungary |
| ID | Ινδονησία | Indonesia |
| IE | Ιρλανδία | Ireland |
| IL | Ισραήλ | Israel |
| IM | Νήσος του Μαν | Isle of Man |
| IN | Ινδία | India |
| IO | Βρετανικό Έδαφος Ινδικού Ωκεανού | British Indian Ocean Territory |
| IQ | Ιράκ | Iraq |
| IR | Ιράν | Iran, Islamic Republic of |
| IS | Ισλανδία | Iceland |
| IT | Ιταλία | Italy |
| JE | Τζέρσεϊ | Jersey |
| JM | Τζαμάικα | Jamaica |
| JO | Ιορδανία | Jordan |
| JP | Ιαπωνία | Japan |
| KE | Κένυα | Kenya |
| KG | Κιργιζία | Kyrgyzstan |
| KH | Καμπότζη | Cambodia |
| KI | Κιριμπάτι | Kiribati |
| KM | Κομόρες | Comoros |
| KN | Άγιος Χριστόφορος και Νέβις | Saint Kitts and Nevis |
| KP | Βόρεια Κορέα | Korea, Democratic People's Republic of |
| KR | Νότια Κορέα | Korea, Republic of |
| KW | Κουβέιτ | Kuwait |
| KY | Νήσοι Κέιμαν | Cayman Islands |
| KZ | Καζακστάν | Kazakhstan |
| LA | Λάος | Lao People's Democratic Republic |
| LB | Λίβανος | Lebanon |
| LC | Άγιος Λουκίας | Saint Lucia |
| LI | Λιχτενστάιν | Liechtenstein |
| LK | Σρι Λάνκα | Sri Lanka |
| LR | Λιβερία | Liberia |
| LS | Λεσότο | Lesotho |
| LT | Λιθουανία | Lithuania |
| LU | Λουξεμβούργο | Luxembourg |
| LV | Λετονία | Latvia |
| LY | Λιβύη | Libya |
| MA | Μαρόκο | Morocco |
| MC | Μονακό | Monaco |
| MD | Μολδαβία | Moldova, Republic of |
| ME | Μαυροβούνιο | Montenegro |
| MF | Άγιος Μαρτίνος (Γαλλικό τμήμα) | Saint Martin (French part) |
| MG | Μαδαγασκάρη | Madagascar |
| MH | Νήσοι Μάρσαλ | Marshall Islands |
| MK | Βόρεια Μακεδονία | North Macedonia |
| ML | Μάλι | Mali |
| MM | Μιανμάρ | Myanmar |
| MN | Μογγολία | Mongolia |
| MO | Μακάο | Macao |
| MP | Βόρειες Μαριάνες Νήσοι | Northern Mariana Islands |
| MQ | Μαρτινίκα | Martinique |
| MR | Μαυριτανία | Mauritania |
| MS | Μοντσεράτ | Montserrat |
| MT | Μάλτα | Malta |
| MU | Μαυρίκιος | Mauritius |
| MV | Μαλδίβες | Maldives |
| MW | Μαλάουι | Malawi |
| MX | Μεξικό | Mexico |
| MY | Μαλαισία | Malaysia |
| MZ | Μοζαμβίκη | Mozambique |
| NA | Ναμίμπια | Namibia |
| NC | Νέα Καληδονία | New Caledonia |
| NE | Νίγηρας | Niger |
| NF | Νήσος Νόρφολκ | Norfolk Island |
| NG | Νιγηρία | Nigeria |
| NI | Νικαράγουα | Nicaragua |
| NL | Ολλανδία | Netherlands |
| NO | Νορβηγία | Norway |
| NP | Νεπάλ | Nepal |
| NR | Ναουρού | Nauru |
| NU | Νιούε | Niue |
| NZ | Νέα Ζηλανδία | New Zealand |
| OC | Ωκεανία | Oceania |
| OM | Ομάν | Oman |
| PA | Παναμάς | Panama |
| PE | Περού | Peru |
| PF | Γαλλική Πολυνησία | French Polynesia |
| PG | Παπούα Νέα Γουινέα | Papua New Guinea |
| PH | Φιλιππίνες | Philippines |
| PK | Πακιστάν | Pakistan |
| PL | Πολωνία | Poland |
| PM | Σαιν Πιερ και Μικελόν | Saint Pierre and Miquelon |
| PN | Νήσοι Πίτκαιρν | Pitcairn |
| PR | Πουέρτο Ρίκο | Puerto Rico |
| PS | Παλαιστίνη | Palestine, State of |
| PT | Πορτογαλία | Portugal |
| PW | Παλάου | Palau |
| PY | Παραγουάη | Paraguay |
| QA | Κατάρ | Qatar |
| RE | Ρεϊνιόν | Réunion |
| RO | Ρουμανία | Romania |
| RS | Σερβία | Serbia |
| RU | Ρωσία | Russian Federation |
| RW | Ρουάντα | Rwanda |
| SA | Σαουδική Αραβία | Saudi Arabia |
| SB | Νήσοι Σολομώντος | Solomon Islands |
| SC | Σεϋχέλλες | Seychelles |
| SD | Σουδάν | Sudan |
| SE | Σουηδία | Sweden |
| SG | Σιγκαπούρη | Singapore |
| SH | Νήσος Αγίας Ελένης | Saint Helena, Ascension and Tristan da Cunha |
| SI | Σλοβενία | Slovenia |
| SJ | Σβάλμπαρντ και Γιαν Μαγιέν | Svalbard and Jan Mayen |
| SK | Σλοβακία | Slovakia |
| SL | Σιέρα Λεόνε | Sierra Leone |
| SM | Άγιος Μαρίνος | San Marino |
| SN | Σενεγάλη | Senegal |
| SO | Σομαλία | Somalia |
| SR | Σουρινάμ | Suriname |
| ST | Σάο Τομέ και Πρίνσιπε | Sao Tome and Principe |
| SV | Ελ Σαλβαδόρ | El Salvador |
| SY | Συρία | Syrian Arab Republic |
| SZ | Σουαζιλάνδη | Eswatini |
| TC | Νήσοι Τερκς και Κάικος | Turks and Caicos Islands |
| TD | Τσαντ | Chad |
| TF | Γαλλικά Νότια και Ανταρκτικά Εδάφη | French Southern Territories |
| TG | Τόγκο | Togo |
| TH | Ταϊλάνδη | Thailand |
| TJ | Τατζικιστάν | Tajikistan |
| TK | Τοκελάου | Tokelau |
| TL | Ανατολικό Τιμόρ | Timor-Leste |
| TM | Τουρκμενιστάν | Turkmenistan |
| TN | Τυνησία | Tunisia |
| TO | Τόνγκα | Tonga |
| TR | Τουρκία | Turkey |
| TT | Τρινιντάντ και Τομπάγκο | Trinidad and Tobago |
| TV | Τουβαλού | Tuvalu |
| TW | Ταϊβάν | Taiwan, Province of China |
| TZ | Τανζανία | Tanzania, United Republic of |
| UA | Ουκρανία | Ukraine |
| UG | Ουγκάντα | Uganda |
| UM | Απομακρυσμένες Νησίδες Ηνωμένων Πολιτειών | United States Minor Outlying Islands |
| US | Ηνωμένες Πολιτείες | United States |
| UY | Ουρουγουάη | Uruguay |
| UZ | Ουζμπεκιστάν | Uzbekistan |
| VA | Βατικανό | Holy See (Vatican City State) |
| VC | Άγιος Βικέντιος και Γρεναδίνες | Saint Vincent and the Grenadines |
| VE | Βενεζουέλα | Venezuela, Bolivarian Republic of |
| VG | Βρετανικές Παρθένοι Νήσοι | Virgin Islands, British |
| VI | Αμερικανικές Παρθένοι Νήσοι | Virgin Islands, U.S. |
| VN | Βιετνάμ | Viet Nam |
| VU | Βανουάτου | Vanuatu |
| WF | Ουάλις και Φουτούνα | Wallis and Futuna |
| WS | Σαμόα | Samoa |
| YE | Υεμένη | Yemen |
| YT | Μαγιότ | Mayotte |
| ZA | Νότια Αφρική | South Africa |
| ZM | Ζάμπια | Zambia |
| ZW | Ζιμπάμπουε | Zimbabwe |
