# Currency codes

Currency of the invoice. Enum: `CurrencyCode`. The values are [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) codes, and the case name equals the code.

```php
use Firebed\AadeMyData\Enums\CurrencyCode;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())
    ->setCurrency(CurrencyCode::USD)
    ->setExchangeRate(1.08);
```

| Code | `label()` | English |
|---|---|---|
| AED | Ντιρχάμ των Ηνωμένων Αραβικών Εμιράτων | United Arab Emirates Dirham |
| AFN | Αφγανή Αφγανιστάν | Afghan Afghani |
| ALL | Λεκ Αλβανίας | Albanian Lek |
| AMD | Ντραμ Αρμενίας | Armenian Dram |
| ANG | Γκίλντα Ολλανδικών Αντιλλών | Netherlands Antillean Guilder |
| AOA | Κουάνζα Αγκόλας | Angolan Kwanza |
| ARS | Πέσο Αργεντινής | Argentine Peso |
| AUD | Δολάριο Αυστραλίας | Australian Dollar |
| AWG | Φλορίνι Αρούμπα | Aruban Florin |
| AZN | Μανάτ Αζερμπαϊτζάν | Azerbaijani Manat |
| BAM | Μάρκο Βοσνίας-Ερζεγοβίνης | Bosnia and Herzegovina Convertible Mark |
| BBD | Δολάριο Μπαρμπάντος | Barbadian Dollar |
| BDT | Τάκα Μπαγκλαντές | Bangladeshi Taka |
| BGN | Λεβ Βουλγαρίας | Bulgarian Lev |
| BHD | Δηνάριο Μπαχρέιν | Bahraini Dinar |
| BIF | Φράγκο Μπουρούντι | Burundian Franc |
| BMD | Δολάριο Βερμούδων | Bermudian Dollar |
| BND | Δολάριο Μπρουνέι | Brunei Dollar |
| BOB | Μπολιβιάνο Βολιβίας | Bolivian Boliviano |
| BOV | Mvdol Βολιβίας | Bolivian Mvdol (funds code) |
| BRL | Ρεάλ Βραζιλίας | Brazilian Real |
| BSD | Δολάριο Μπαχαμών | Bahamian Dollar |
| BTN | Νγκούλτρουμ Μπουτάν | Bhutanese Ngultrum |
| BWP | Πούλα Μποτσουάνα | Botswana Pula |
| BYR | Ρούβλι Λευκορωσίας | Belarusian Ruble |
| BZD | Δολάριο Μπελίζ | Belize Dollar |
| CAD | Δολάριο Καναδά | Canadian Dollar |
| CDF | Φράγκο Κονγκό | Congolese Franc |
| CHF | Φράγκο Ελβετίας | Swiss Franc |
| CLF | Unidades de fomento Χιλής | Chilean Unit of Account (UF) |
| CLP | Πέσο Χιλής | Chilean Peso |
| CNY | Γιουάν Κίνας | Chinese Yuan |
| COP | Πέσο Κολομβίας | Colombian Peso |
| COU | Unidad de Valor Real Κολομβίας | Colombian Real Value Unit |
| CRC | Κολόν Κόστα Ρίκα | Costa Rican Colón |
| CUC | Μετατρέψιμο πέσο Κούβας | Cuban Convertible Peso |
| CUP | Πέσο Κούβας | Cuban Peso |
| CVE | Εσκούδο Πράσινου Ακρωτηρίου | Cape Verdean Escudo |
| CZK | Κορόνα Τσεχίας | Czech Koruna |
| DJF | Φράγκο Τζιμπουτί | Djiboutian Franc |
| DKK | Κορόνα Δανίας | Danish Krone |
| DOP | Πέσο Δομινικανής Δημοκρατίας | Dominican Peso |
| DZD | Δηνάριο Αλγερίας | Algerian Dinar |
| EEK | Κρούνα Εσθονίας | Estonian Kroon (before Euro) |
| EGP | Λίρα Αιγύπτου | Egyptian Pound |
| ERN | Νάκφα Ερυθραίας | Eritrean Nakfa |
| ETB | Μπιρ Αιθιοπίας | Ethiopian Birr |
| EUR | Ευρώ | Euro |
| FJD | Δολάριο Φίτζι | Fijian Dollar |
| FKP | Λίρα Νήσων Φόκλαντ | Falkland Islands Pound |
| GBP | Λίρα Στερλίνα | British Pound Sterling |
| GEL | Λάρι Γεωργίας | Georgian Lari |
| GHS | Σέντι Γκάνας | Ghanaian Cedi |
| GIP | Λίρα Γιβραλτάρ | Gibraltar Pound |
| GMD | Νταλάσι Γκάμπιας | Gambian Dalasi |
| GNF | Φράγκο Γουινέας | Guinean Franc |
| GTQ | Κετσάλ Γουατεμάλας | Guatemalan Quetzal |
| GWP | Πέσο Γουινέας-Μπισσάου | Guinea-Bissau Peso |
| GYD | Δολάριο Γουιάνας | Guyanese Dollar |
| HKD | Δολάριο Χονγκ Κονγκ | Hong Kong Dollar |
| HNL | Λεμπίρα Ονδούρας | Honduran Lempira |
| HRK | Κούνα Κροατίας | Croatian Kuna |
| HTG | Γκουρντ Αϊτής | Haitian Gourde |
| HUF | Φιορίνι Ουγγαρίας | Hungarian Forint |
| IDR | Ρουπία Ινδονησίας | Indonesian Rupiah |
| ILS | Νέο Σέκελ Ισραήλ | Israeli New Shekel |
| INR | Ρουπία Ινδίας | Indian Rupee |
| IQD | Δηνάριο Ιράκ | Iraqi Dinar |
| IRR | Ριάλ Ιράν | Iranian Rial |
| ISK | Κορόνα Ισλανδίας | Icelandic Króna |
| JMD | Δολάριο Τζαμάικας | Jamaican Dollar |
| JOD | Δηνάριο Ιορδανίας | Jordanian Dinar |
| JPY | Γιεν Ιαπωνίας | Japanese Yen |
| KES | Σελίνι Κένυας | Kenyan Shilling |
| KGS | Σομ Κιργιζίας | Kyrgyzstani Som |
| KHR | Ρίελ Καμπότζης | Cambodian Riel |
| KMF | Φράγκο Κομόρων | Comorian Franc |
| KPW | Γουόν Βόρειας Κορέας | North Korean Won |
| KRW | Γουόν Νότιας Κορέας | South Korean Won |
| KWD | Δηνάριο Κουβέιτ | Kuwaiti Dinar |
| KYD | Δολάριο Νήσων Κέιμαν | Cayman Islands Dollar |
| KZT | Τένγκε Καζακστάν | Kazakhstani Tenge |
| LAK | Κιπ Λάος | Lao Kip |
| LBP | Λίρα Λιβάνου | Lebanese Pound |
| LKR | Ρουπία Σρι Λάνκα | Sri Lankan Rupee |
| LRD | Δολάριο Λιβερίας | Liberian Dollar |
| LSL | Λότι Λεσότο | Lesotho Loti |
| LTL | Λίτας Λιθουανίας | Lithuanian Litas (before Euro) |
| LVL | Λατς Λετονίας | Latvian Lats (before Euro) |
| LYD | Δηνάριο Λιβύης | Libyan Dinar |
| MAD | Ντιράμ Μαρόκου | Moroccan Dirham |
| MDL | Λέου Μολδαβίας | Moldovan Leu |
| MGA | Αριάρι Μαδαγασκάρης | Malagasy Ariary |
| MKD | Δηνάριο Βόρειας Μακεδονίας | Macedonian Denar |
| MMK | Κιάτ Μιανμάρ | Myanmar Kyat |
| MNT | Τουγκρίκ Μογγολίας | Mongolian Tögrög |
| MOP | Πατάκα Μακάο | Macanese Pataca |
| MRO | Ουγκίγια Μαυριτανίας | Mauritanian Ouguiya (before 2018) |
| MUR | Ρουπία Μαυρίκιου | Mauritian Rupee |
| MVR | Ρουφίγια Μαλδίβων | Maldivian Rufiyaa |
| MWK | Κουάτσα Μαλάουι | Malawian Kwacha |
| MXN | Πέσο Μεξικού | Mexican Peso |
| MXV | Unidad de Inversion Μεξικού | Mexican Unidad de Inversion (UDI) |
| MYR | Ρινγκίτ Μαλαισίας | Malaysian Ringgit |
| MZN | Μετικάλ Μοζαμβίκης | Mozambican Metical |
| NAD | Δολάριο Ναμίμπιας | Namibian Dollar |
| NGN | Νάιρα Νιγηρίας | Nigerian Naira |
| NIO | Κόρδοβα Νικαράγουας | Nicaraguan Córdoba |
| NOK | Κορόνα Νορβηγίας | Norwegian Krone |
| NPR | Ρουπία Νεπάλ | Nepalese Rupee |
| NZD | Δολάριο Νέας Ζηλανδίας | New Zealand Dollar |
| OMR | Ριάλ Ομάν | Omani Rial |
| PAB | Μπαλμπόα Παναμά | Panamanian Balboa |
| PEN | Σολ Περού | Peruvian Sol |
| PGK | Κίνα Παπούας Νέας Γουινέας | Papua New Guinean Kina |
| PHP | Πέσο Φιλιππίνων | Philippine Peso |
| PKR | Ρουπία Πακιστάν | Pakistani Rupee |
| PLN | Ζλότι Πολωνίας | Polish Złoty |
| PYG | Γκουαρανί Παραγουάης | Paraguayan Guaraní |
| QAR | Ριάλ Κατάρ | Qatari Riyal |
| RON | Λέου Ρουμανίας | Romanian Leu |
| RSD | Δηνάριο Σερβίας | Serbian Dinar |
| RUB | Ρούβλι Ρωσίας | Russian Ruble |
| RWF | Φράγκο Ρουάντας | Rwandan Franc |
| SAR | Ριάλ Σαουδικής Αραβίας | Saudi Riyal |
| SBD | Δολάριο Νήσων Σολομώντος | Solomon Islands Dollar |
| SCR | Ρουπία Σεϋχελλών | Seychellois Rupee |
| SDG | Λίρα Σουδάν | Sudanese Pound |
| SEK | Κορόνα Σουηδίας | Swedish Krona |
| SGD | Δολάριο Σιγκαπούρης | Singapore Dollar |
| SHP | Λίρα Αγίας Ελένης | Saint Helena Pound |
| SLL | Λεόνε Σιέρα Λεόνε | Sierra Leonean Leone |
| SOS | Σελίνι Σομαλίας | Somali Shilling |
| SRD | Δολάριο Σουρινάμ | Surinamese Dollar |
| STD | Ντόμπρα Σάο Τομέ και Πρίνσιπε | São Tomé and Príncipe Dobra (before 2018) |
| SVC | Κολόν Ελ Σαλβαδόρ | Salvadoran Colón |
| SYP | Λίρα Συρίας | Syrian Pound |
| SZL | Λιλανγκένι Σουαζιλάνδης | Eswatini Lilangeni |
| THB | Μπατ Ταϊλάνδης | Thai Baht |
| TJS | Σομόνι Τατζικιστάν | Tajikistani Somoni |
| TMT | Μανάτ Τουρκμενιστάν | Turkmenistani Manat |
| TND | Δηνάριο Τυνησίας | Tunisian Dinar |
| TOP | Παάνγκα της Τόνγκα | Tongan Paʻanga |
| TRY | Λίρα Τουρκίας | Turkish Lira |
| TTD | Δολάριο Τρινιντάντ και Τομπάγκο | Trinidad and Tobago Dollar |
| TVD | Δολάριο Τουβαλού | Tuvaluan Dollar (unofficial) |
| TWD | Νέο δολάριο Ταϊβάν | New Taiwan Dollar |
| TZS | Σελίνι Τανζανίας | Tanzanian Shilling |
| UAH | Ρίβνια Ουκρανίας | Ukrainian Hryvnia |
| UGX | Σελίνι Ουγκάντας | Ugandan Shilling |
| USD | Δολάριο Ηνωμένων Πολιτειών | United States Dollar |
| UYU | Πέσο Ουρουγουάης | Uruguayan Peso |
| UZS | Σομ Ουζμπεκιστάν | Uzbekistani Som |
| VEF | Μπολιβάρ Βενεζουέλας | Venezuelan Bolívar (before 2018) |
| VND | Ντονγκ Βιετνάμ | Vietnamese Đồng |
| VUV | Βατού Βανουάτου | Vanuatu Vatu |
| WST | Τάλα του Σαμόα | Samoan Tala |
| XAF | Φράγκο CFA Κεντρικής Αφρικής | Central African CFA Franc |
| XCD | Δολάριο Ανατολικής Καραϊβικής | East Caribbean Dollar |
| XOF | Φράγκο CFA Δυτικής Αφρικής | West African CFA Franc |
| XPD | Παλάδιο | Palladium (one troy ounce) |
| XPF | Φράγκο CFP | CFP Franc (used in French Pacific territories) |
| YER | Ριάλ Υεμένης | Yemeni Rial |
| ZAR | Ραντ Νότιας Αφρικής | South African Rand |
| ZMK | Κουάτσα Ζάμπιας | Zambian Kwacha (before 2013) |
| ZWL | Δολάριο Ζιμπάμπουε | Zimbabwean Dollar (2009) |
