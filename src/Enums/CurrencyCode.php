<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum CurrencyCode: string
{
    use HasLabels;

    #[Label('Ντιρχάμ των Ηνωμένων Αραβικών Εμιράτων')]
    case AED = 'AED'; // United Arab Emirates Dirham
    #[Label('Αφγανή Αφγανιστάν')]
    case AFN = 'AFN'; // Afghan Afghani
    #[Label('Λεκ Αλβανίας')]
    case ALL = 'ALL'; // Albanian Lek
    #[Label('Ντραμ Αρμενίας')]
    case AMD = 'AMD'; // Armenian Dram
    #[Label('Γκίλντα Ολλανδικών Αντιλλών')]
    case ANG = 'ANG'; // Netherlands Antillean Guilder
    #[Label('Κουάνζα Αγκόλας')]
    case AOA = 'AOA'; // Angolan Kwanza
    #[Label('Πέσο Αργεντινής')]
    case ARS = 'ARS'; // Argentine Peso
    #[Label('Δολάριο Αυστραλίας')]
    case AUD = 'AUD'; // Australian Dollar
    #[Label('Φλορίνι Αρούμπα')]
    case AWG = 'AWG'; // Aruban Florin
    #[Label('Μανάτ Αζερμπαϊτζάν')]
    case AZN = 'AZN'; // Azerbaijani Manat
    #[Label('Μάρκο Βοσνίας-Ερζεγοβίνης')]
    case BAM = 'BAM'; // Bosnia and Herzegovina Convertible Mark
    #[Label('Δολάριο Μπαρμπάντος')]
    case BBD = 'BBD'; // Barbadian Dollar
    #[Label('Τάκα Μπαγκλαντές')]
    case BDT = 'BDT'; // Bangladeshi Taka
    #[Label('Λεβ Βουλγαρίας')]
    case BGN = 'BGN'; // Bulgarian Lev
    #[Label('Δηνάριο Μπαχρέιν')]
    case BHD = 'BHD'; // Bahraini Dinar
    #[Label('Φράγκο Μπουρούντι')]
    case BIF = 'BIF'; // Burundian Franc
    #[Label('Δολάριο Βερμούδων')]
    case BMD = 'BMD'; // Bermudian Dollar
    #[Label('Δολάριο Μπρουνέι')]
    case BND = 'BND'; // Brunei Dollar
    #[Label('Μπολιβιάνο Βολιβίας')]
    case BOB = 'BOB'; // Bolivian Boliviano
    #[Label('Mvdol Βολιβίας')]
    case BOV = 'BOV'; // Bolivian Mvdol (funds code)
    #[Label('Ρεάλ Βραζιλίας')]
    case BRL = 'BRL'; // Brazilian Real
    #[Label('Δολάριο Μπαχαμών')]
    case BSD = 'BSD'; // Bahamian Dollar
    #[Label('Νγκούλτρουμ Μπουτάν')]
    case BTN = 'BTN'; // Bhutanese Ngultrum
    #[Label('Πούλα Μποτσουάνα')]
    case BWP = 'BWP'; // Botswana Pula
    #[Label('Ρούβλι Λευκορωσίας')]
    case BYR = 'BYR'; // Belarusian Ruble
    #[Label('Δολάριο Μπελίζ')]
    case BZD = 'BZD'; // Belize Dollar
    #[Label('Δολάριο Καναδά')]
    case CAD = 'CAD'; // Canadian Dollar
    #[Label('Φράγκο Κονγκό')]
    case CDF = 'CDF'; // Congolese Franc
    #[Label('Φράγκο Ελβετίας')]
    case CHF = 'CHF'; // Swiss Franc
    #[Label('Unidades de fomento Χιλής')]
    case CLF = 'CLF'; // Chilean Unit of Account (UF)
    #[Label('Πέσο Χιλής')]
    case CLP = 'CLP'; // Chilean Peso
    #[Label('Γιουάν Κίνας')]
    case CNY = 'CNY'; // Chinese Yuan
    #[Label('Πέσο Κολομβίας')]
    case COP = 'COP'; // Colombian Peso
    #[Label('Unidad de Valor Real Κολομβίας')]
    case COU = 'COU'; // Colombian Real Value Unit
    #[Label('Κολόν Κόστα Ρίκα')]
    case CRC = 'CRC'; // Costa Rican Colón
    #[Label('Μετατρέψιμο πέσο Κούβας')]
    case CUC = 'CUC'; // Cuban Convertible Peso
    #[Label('Πέσο Κούβας')]
    case CUP = 'CUP'; // Cuban Peso
    #[Label('Εσκούδο Πράσινου Ακρωτηρίου')]
    case CVE = 'CVE'; // Cape Verdean Escudo
    #[Label('Κορόνα Τσεχίας')]
    case CZK = 'CZK'; // Czech Koruna
    #[Label('Φράγκο Τζιμπουτί')]
    case DJF = 'DJF'; // Djiboutian Franc
    #[Label('Κορόνα Δανίας')]
    case DKK = 'DKK'; // Danish Krone
    #[Label('Πέσο Δομινικανής Δημοκρατίας')]
    case DOP = 'DOP'; // Dominican Peso
    #[Label('Δηνάριο Αλγερίας')]
    case DZD = 'DZD'; // Algerian Dinar
    #[Label('Κρούνα Εσθονίας')]
    case EEK = 'EEK'; // Estonian Kroon (before Euro)
    #[Label('Λίρα Αιγύπτου')]
    case EGP = 'EGP'; // Egyptian Pound
    #[Label('Νάκφα Ερυθραίας')]
    case ERN = 'ERN'; // Eritrean Nakfa
    #[Label('Μπιρ Αιθιοπίας')]
    case ETB = 'ETB'; // Ethiopian Birr
    #[Label('Ευρώ')]
    case EUR = 'EUR'; // Euro
    #[Label('Δολάριο Φίτζι')]
    case FJD = 'FJD'; // Fijian Dollar
    #[Label('Λίρα Νήσων Φόκλαντ')]
    case FKP = 'FKP'; // Falkland Islands Pound
    #[Label('Λίρα Στερλίνα')]
    case GBP = 'GBP'; // British Pound Sterling
    #[Label('Λάρι Γεωργίας')]
    case GEL = 'GEL'; // Georgian Lari
    #[Label('Σέντι Γκάνας')]
    case GHS = 'GHS'; // Ghanaian Cedi
    #[Label('Λίρα Γιβραλτάρ')]
    case GIP = 'GIP'; // Gibraltar Pound
    #[Label('Νταλάσι Γκάμπιας')]
    case GMD = 'GMD'; // Gambian Dalasi
    #[Label('Φράγκο Γουινέας')]
    case GNF = 'GNF'; // Guinean Franc
    #[Label('Κετσάλ Γουατεμάλας')]
    case GTQ = 'GTQ'; // Guatemalan Quetzal
    #[Label('Πέσο Γουινέας-Μπισσάου')]
    case GWP = 'GWP'; // Guinea-Bissau Peso
    #[Label('Δολάριο Γουιάνας')]
    case GYD = 'GYD'; // Guyanese Dollar
    #[Label('Δολάριο Χονγκ Κονγκ')]
    case HKD = 'HKD'; // Hong Kong Dollar
    #[Label('Λεμπίρα Ονδούρας')]
    case HNL = 'HNL'; // Honduran Lempira
    #[Label('Κούνα Κροατίας')]
    case HRK = 'HRK'; // Croatian Kuna
    #[Label('Γκουρντ Αϊτής')]
    case HTG = 'HTG'; // Haitian Gourde
    #[Label('Φιορίνι Ουγγαρίας')]
    case HUF = 'HUF'; // Hungarian Forint
    #[Label('Ρουπία Ινδονησίας')]
    case IDR = 'IDR'; // Indonesian Rupiah
    #[Label('Νέο Σέκελ Ισραήλ')]
    case ILS = 'ILS'; // Israeli New Shekel
    #[Label('Ρουπία Ινδίας')]
    case INR = 'INR'; // Indian Rupee
    #[Label('Δηνάριο Ιράκ')]
    case IQD = 'IQD'; // Iraqi Dinar
    #[Label('Ριάλ Ιράν')]
    case IRR = 'IRR'; // Iranian Rial
    #[Label('Κορόνα Ισλανδίας')]
    case ISK = 'ISK'; // Icelandic Króna
    #[Label('Δολάριο Τζαμάικας')]
    case JMD = 'JMD'; // Jamaican Dollar
    #[Label('Δηνάριο Ιορδανίας')]
    case JOD = 'JOD'; // Jordanian Dinar
    #[Label('Γιεν Ιαπωνίας')]
    case JPY = 'JPY'; // Japanese Yen
    #[Label('Σελίνι Κένυας')]
    case KES = 'KES'; // Kenyan Shilling
    #[Label('Σομ Κιργιζίας')]
    case KGS = 'KGS'; // Kyrgyzstani Som
    #[Label('Ρίελ Καμπότζης')]
    case KHR = 'KHR'; // Cambodian Riel
    #[Label('Φράγκο Κομόρων')]
    case KMF = 'KMF'; // Comorian Franc
    #[Label('Γουόν Βόρειας Κορέας')]
    case KPW = 'KPW'; // North Korean Won
    #[Label('Γουόν Νότιας Κορέας')]
    case KRW = 'KRW'; // South Korean Won
    #[Label('Δηνάριο Κουβέιτ')]
    case KWD = 'KWD'; // Kuwaiti Dinar
    #[Label('Δολάριο Νήσων Κέιμαν')]
    case KYD = 'KYD'; // Cayman Islands Dollar
    #[Label('Τένγκε Καζακστάν')]
    case KZT = 'KZT'; // Kazakhstani Tenge
    #[Label('Κιπ Λάος')]
    case LAK = 'LAK'; // Lao Kip
    #[Label('Λίρα Λιβάνου')]
    case LBP = 'LBP'; // Lebanese Pound
    #[Label('Ρουπία Σρι Λάνκα')]
    case LKR = 'LKR'; // Sri Lankan Rupee
    #[Label('Δολάριο Λιβερίας')]
    case LRD = 'LRD'; // Liberian Dollar
    #[Label('Λότι Λεσότο')]
    case LSL = 'LSL'; // Lesotho Loti
    #[Label('Λίτας Λιθουανίας')]
    case LTL = 'LTL'; // Lithuanian Litas (before Euro)
    #[Label('Λατς Λετονίας')]
    case LVL = 'LVL'; // Latvian Lats (before Euro)
    #[Label('Δηνάριο Λιβύης')]
    case LYD = 'LYD'; // Libyan Dinar
    #[Label('Ντιράμ Μαρόκου')]
    case MAD = 'MAD'; // Moroccan Dirham
    #[Label('Λέου Μολδαβίας')]
    case MDL = 'MDL'; // Moldovan Leu
    #[Label('Αριάρι Μαδαγασκάρης')]
    case MGA = 'MGA'; // Malagasy Ariary
    #[Label('Δηνάριο Βόρειας Μακεδονίας')]
    case MKD = 'MKD'; // Macedonian Denar
    #[Label('Κιάτ Μιανμάρ')]
    case MMK = 'MMK'; // Myanmar Kyat
    #[Label('Τουγκρίκ Μογγολίας')]
    case MNT = 'MNT'; // Mongolian Tögrög
    #[Label('Πατάκα Μακάο')]
    case MOP = 'MOP'; // Macanese Pataca
    #[Label('Ουγκίγια Μαυριτανίας')]
    case MRO = 'MRO'; // Mauritanian Ouguiya (before 2018)
    #[Label('Ρουπία Μαυρίκιου')]
    case MUR = 'MUR'; // Mauritian Rupee
    #[Label('Ρουφίγια Μαλδίβων')]
    case MVR = 'MVR'; // Maldivian Rufiyaa
    #[Label('Κουάτσα Μαλάουι')]
    case MWK = 'MWK'; // Malawian Kwacha
    #[Label('Πέσο Μεξικού')]
    case MXN = 'MXN'; // Mexican Peso
    #[Label('Unidad de Inversion Μεξικού')]
    case MXV = 'MXV'; // Mexican Unidad de Inversion (UDI)
    #[Label('Ρινγκίτ Μαλαισίας')]
    case MYR = 'MYR'; // Malaysian Ringgit
    #[Label('Μετικάλ Μοζαμβίκης')]
    case MZN = 'MZN'; // Mozambican Metical
    #[Label('Δολάριο Ναμίμπιας')]
    case NAD = 'NAD'; // Namibian Dollar
    #[Label('Νάιρα Νιγηρίας')]
    case NGN = 'NGN'; // Nigerian Naira
    #[Label('Κόρδοβα Νικαράγουας')]
    case NIO = 'NIO'; // Nicaraguan Córdoba
    #[Label('Κορόνα Νορβηγίας')]
    case NOK = 'NOK'; // Norwegian Krone
    #[Label('Ρουπία Νεπάλ')]
    case NPR = 'NPR'; // Nepalese Rupee
    #[Label('Δολάριο Νέας Ζηλανδίας')]
    case NZD = 'NZD'; // New Zealand Dollar
    #[Label('Ριάλ Ομάν')]
    case OMR = 'OMR'; // Omani Rial
    #[Label('Μπαλμπόα Παναμά')]
    case PAB = 'PAB'; // Panamanian Balboa
    #[Label('Σολ Περού')]
    case PEN = 'PEN'; // Peruvian Sol
    #[Label('Κίνα Παπούας Νέας Γουινέας')]
    case PGK = 'PGK'; // Papua New Guinean Kina
    #[Label('Πέσο Φιλιππίνων')]
    case PHP = 'PHP'; // Philippine Peso
    #[Label('Ρουπία Πακιστάν')]
    case PKR = 'PKR'; // Pakistani Rupee
    #[Label('Ζλότι Πολωνίας')]
    case PLN = 'PLN'; // Polish Złoty
    #[Label('Γκουαρανί Παραγουάης')]
    case PYG = 'PYG'; // Paraguayan Guaraní
    #[Label('Ριάλ Κατάρ')]
    case QAR = 'QAR'; // Qatari Riyal
    #[Label('Λέου Ρουμανίας')]
    case RON = 'RON'; // Romanian Leu
    #[Label('Δηνάριο Σερβίας')]
    case RSD = 'RSD'; // Serbian Dinar
    #[Label('Ρούβλι Ρωσίας')]
    case RUB = 'RUB'; // Russian Ruble
    #[Label('Φράγκο Ρουάντας')]
    case RWF = 'RWF'; // Rwandan Franc
    #[Label('Ριάλ Σαουδικής Αραβίας')]
    case SAR = 'SAR'; // Saudi Riyal
    #[Label('Δολάριο Νήσων Σολομώντος')]
    case SBD = 'SBD'; // Solomon Islands Dollar
    #[Label('Ρουπία Σεϋχελλών')]
    case SCR = 'SCR'; // Seychellois Rupee
    #[Label('Λίρα Σουδάν')]
    case SDG = 'SDG'; // Sudanese Pound
    #[Label('Κορόνα Σουηδίας')]
    case SEK = 'SEK'; // Swedish Krona
    #[Label('Δολάριο Σιγκαπούρης')]
    case SGD = 'SGD'; // Singapore Dollar
    #[Label('Λίρα Αγίας Ελένης')]
    case SHP = 'SHP'; // Saint Helena Pound
    #[Label('Λεόνε Σιέρα Λεόνε')]
    case SLL = 'SLL'; // Sierra Leonean Leone
    #[Label('Σελίνι Σομαλίας')]
    case SOS = 'SOS'; // Somali Shilling
    #[Label('Δολάριο Σουρινάμ')]
    case SRD = 'SRD'; // Surinamese Dollar
    #[Label('Ντόμπρα Σάο Τομέ και Πρίνσιπε')]
    case STD = 'STD'; // São Tomé and Príncipe Dobra (before 2018)
    #[Label('Κολόν Ελ Σαλβαδόρ')]
    case SVC = 'SVC'; // Salvadoran Colón
    #[Label('Λίρα Συρίας')]
    case SYP = 'SYP'; // Syrian Pound
    #[Label('Λιλανγκένι Σουαζιλάνδης')]
    case SZL = 'SZL'; // Eswatini Lilangeni
    #[Label('Μπατ Ταϊλάνδης')]
    case THB = 'THB'; // Thai Baht
    #[Label('Σομόνι Τατζικιστάν')]
    case TJS = 'TJS'; // Tajikistani Somoni
    #[Label('Μανάτ Τουρκμενιστάν')]
    case TMT = 'TMT'; // Turkmenistani Manat
    #[Label('Δηνάριο Τυνησίας')]
    case TND = 'TND'; // Tunisian Dinar
    #[Label('Παάνγκα της Τόνγκα')]
    case TOP = 'TOP'; // Tongan Paʻanga
    #[Label('Λίρα Τουρκίας')]
    case TRY = 'TRY'; // Turkish Lira
    #[Label('Δολάριο Τρινιντάντ και Τομπάγκο')]
    case TTD = 'TTD'; // Trinidad and Tobago Dollar
    #[Label('Δολάριο Τουβαλού')]
    case TVD = 'TVD'; // Tuvaluan Dollar (unofficial)
    #[Label('Νέο δολάριο Ταϊβάν')]
    case TWD = 'TWD'; // New Taiwan Dollar
    #[Label('Σελίνι Τανζανίας')]
    case TZS = 'TZS'; // Tanzanian Shilling
    #[Label('Ρίβνια Ουκρανίας')]
    case UAH = 'UAH'; // Ukrainian Hryvnia
    #[Label('Σελίνι Ουγκάντας')]
    case UGX = 'UGX'; // Ugandan Shilling
    #[Label('Δολάριο Ηνωμένων Πολιτειών')]
    case USD = 'USD'; // United States Dollar
    #[Label('Πέσο Ουρουγουάης')]
    case UYU = 'UYU'; // Uruguayan Peso
    #[Label('Σομ Ουζμπεκιστάν')]
    case UZS = 'UZS'; // Uzbekistani Som
    #[Label('Μπολιβάρ Βενεζουέλας')]
    case VEF = 'VEF'; // Venezuelan Bolívar (before 2018)
    #[Label('Ντονγκ Βιετνάμ')]
    case VND = 'VND'; // Vietnamese Đồng
    #[Label('Βατού Βανουάτου')]
    case VUV = 'VUV'; // Vanuatu Vatu
    #[Label('Τάλα του Σαμόα')]
    case WST = 'WST'; // Samoan Tala
    #[Label('Φράγκο CFA Κεντρικής Αφρικής')]
    case XAF = 'XAF'; // Central African CFA Franc
    #[Label('Δολάριο Ανατολικής Καραϊβικής')]
    case XCD = 'XCD'; // East Caribbean Dollar
    #[Label('Φράγκο CFA Δυτικής Αφρικής')]
    case XOF = 'XOF'; // West African CFA Franc
    #[Label('Παλάδιο')]
    case XPD = 'XPD'; // Palladium (one troy ounce)
    #[Label('Φράγκο CFP')]
    case XPF = 'XPF'; // CFP Franc (used in French Pacific territories)
    #[Label('Ριάλ Υεμένης')]
    case YER = 'YER'; // Yemeni Rial
    #[Label('Ραντ Νότιας Αφρικής')]
    case ZAR = 'ZAR'; // South African Rand
    #[Label('Κουάτσα Ζάμπιας')]
    case ZMK = 'ZMK'; // Zambian Kwacha (before 2013)
    #[Label('Δολάριο Ζιμπάμπουε')]
    case ZWL = 'ZWL'; // Zimbabwean Dollar (2009)
}
