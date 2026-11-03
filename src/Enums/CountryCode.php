<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

enum CountryCode: string
{
    use HasLabels;

    #[Label('Ανδόρα')]
    case AD = 'AD'; // Andorra
    #[Label('Ηνωμένα Αραβικά Εμιράτα')]
    case AE = 'AE'; // United Arab Emirates
    #[Label('Αφγανιστάν')]
    case AF = 'AF'; // Afghanistan
    #[Label('Αντίγκουα και Μπαρμπούντα')]
    case AG = 'AG'; // Antigua and Barbuda
    #[Label('Ανγκουίλα')]
    case AI = 'AI'; // Anguilla
    #[Label('Αλβανία')]
    case AL = 'AL'; // Albania
    #[Label('Αρμενία')]
    case AM = 'AM'; // Armenia
    #[Label('Ολλανδικές Αντίλλες')]
    case AN = 'AN'; // Netherlands Antilles
    #[Label('Αγκόλα')]
    case AO = 'AO'; // Angola
    #[Label('Ανταρκτική')]
    case AQ = 'AQ'; // Antarctica
    #[Label('Αργεντινή')]
    case AR = 'AR'; // Argentina
    #[Label('Αμερικανική Σαμόα')]
    case AS = 'AS'; // American Samoa
    #[Label('Αυστρία')]
    case AT = 'AT'; // Austria
    #[Label('Αυστραλία')]
    case AU = 'AU'; // Australia
    #[Label('Αρούμπα')]
    case AW = 'AW'; // Aruba
    #[Label('Νήσοι Όλαντ')]
    case AX = 'AX'; // Åland Islands
    #[Label('Αζερμπαϊτζάν')]
    case AZ = 'AZ'; // Azerbaijan
    #[Label('Βοσνία και Ερζεγοβίνη')]
    case BA = 'BA'; // Bosnia and Herzegovina
    #[Label('Μπαρμπάντος')]
    case BB = 'BB'; // Barbados
    #[Label('Μπαγκλαντές')]
    case BD = 'BD'; // Bangladesh
    #[Label('Βέλγιο')]
    case BE = 'BE'; // Belgium
    #[Label('Μπουρκίνα Φάσο')]
    case BF = 'BF'; // Burkina Faso
    #[Label('Βουλγαρία')]
    case BG = 'BG'; // Bulgaria
    #[Label('Μπαχρέιν')]
    case BH = 'BH'; // Bahrain
    #[Label('Μπουρούντι')]
    case BI = 'BI'; // Burundi
    #[Label('Μπενίν')]
    case BJ = 'BJ'; // Benin
    #[Label('Άγιος Βαρθολομαίος')]
    case BL = 'BL'; // Saint Barthélemy
    #[Label('Βερμούδες')]
    case BM = 'BM'; // Bermuda
    #[Label('Μπρουνέι')]
    case BN = 'BN'; // Brunei Darussalam
    #[Label('Βολιβία')]
    case BO = 'BO'; // Bolivia
    #[Label('Βραζιλία')]
    case BR = 'BR'; // Brazil
    #[Label('Μπαχάμες')]
    case BS = 'BS'; // Bahamas
    #[Label('Μπουτάν')]
    case BT = 'BT'; // Bhutan
    #[Label('Νήσος Μπουβέ')]
    case BV = 'BV'; // Bouvet Island
    #[Label('Μποτσουάνα')]
    case BW = 'BW'; // Botswana
    #[Label('Λευκορωσία')]
    case BY = 'BY'; // Belarus
    #[Label('Μπελίζ')]
    case BZ = 'BZ'; // Belize
    #[Label('Καναδάς')]
    case CA = 'CA'; // Canada
    #[Label('Νήσοι Κόκος (Κίλινγκ)')]
    case CC = 'CC'; // Cocos (Keeling) Islands
    #[Label('Κονγκό (Λαϊκή Δημοκρατία του)')]
    case CD = 'CD'; // Congo, Democratic Republic of the
    #[Label('Κεντροαφρικανική Δημοκρατία')]
    case CF = 'CF'; // Central African Republic
    #[Label('Κονγκό')]
    case CG = 'CG'; // Congo
    #[Label('Ελβετία')]
    case CH = 'CH'; // Switzerland
    #[Label('Ακτή Ελεφαντοστού')]
    case CI = 'CI'; // Côte d'Ivoire
    #[Label('Νήσοι Κουκ')]
    case CK = 'CK'; // Cook Islands
    #[Label('Χιλή')]
    case CL = 'CL'; // Chile
    #[Label('Καμερούν')]
    case CM = 'CM'; // Cameroon
    #[Label('Κίνα')]
    case CN = 'CN'; // China
    #[Label('Κολομβία')]
    case CO = 'CO'; // Colombia
    #[Label('Κόστα Ρίκα')]
    case CR = 'CR'; // Costa Rica
    #[Label('Κούβα')]
    case CU = 'CU'; // Cuba
    #[Label('Πράσινο Ακρωτήριο')]
    case CV = 'CV'; // Cape Verde
    #[Label('Νήσος των Χριστουγέννων')]
    case CX = 'CX'; // Christmas Island
    #[Label('Κύπρος')]
    case CY = 'CY'; // Cyprus
    #[Label('Τσεχία')]
    case CZ = 'CZ'; // Czech Republic
    #[Label('Γερμανία')]
    case DE = 'DE'; // Germany
    #[Label('Τζιμπουτί')]
    case DJ = 'DJ'; // Djibouti
    #[Label('Δανία')]
    case DK = 'DK'; // Denmark
    #[Label('Ντομίνικα')]
    case DM = 'DM'; // Dominica
    #[Label('Δομινικανή Δημοκρατία')]
    case DO = 'DO'; // Dominican Republic
    #[Label('Αλγερία')]
    case DZ = 'DZ'; // Algeria
    #[Label('Εκουαδόρ')]
    case EC = 'EC'; // Ecuador
    #[Label('Εσθονία')]
    case EE = 'EE'; // Estonia
    #[Label('Αίγυπτος')]
    case EG = 'EG'; // Egypt
    #[Label('Δυτική Σαχάρα')]
    case EH = 'EH'; // Western Sahara
    #[Label('Ερυθραία')]
    case ER = 'ER'; // Eritrea
    #[Label('Ισπανία')]
    case ES = 'ES'; // Spain
    #[Label('Αιθιοπία')]
    case ET = 'ET'; // Ethiopia
    #[Label('Φινλανδία')]
    case FI = 'FI'; // Finland
    #[Label('Φίτζι')]
    case FJ = 'FJ'; // Fiji
    #[Label('Νήσοι Φώκλαντ')]
    case FK = 'FK'; // Falkland Islands (Malvinas)
    #[Label('Μικρονησία')]
    case FM = 'FM'; // Micronesia, Federated States of
    #[Label('Νήσοι Φερόες')]
    case FO = 'FO'; // Faroe Islands
    #[Label('Γαλλία')]
    case FR = 'FR'; // France
    #[Label('Γκαμπόν')]
    case GA = 'GA'; // Gabon
    #[Label('Ηνωμένο Βασίλειο')]
    case GB = 'GB'; // United Kingdom
    #[Label('Γρενάδα')]
    case GD = 'GD'; // Grenada
    #[Label('Γεωργία')]
    case GE = 'GE'; // Georgia
    #[Label('Γαλλική Γουιάνα')]
    case GF = 'GF'; // French Guiana
    #[Label('Γκέρνσεϊ')]
    case GG = 'GG'; // Guernsey
    #[Label('Γκάνα')]
    case GH = 'GH'; // Ghana
    #[Label('Γιβραλτάρ')]
    case GI = 'GI'; // Gibraltar
    #[Label('Γροιλανδία')]
    case GL = 'GL'; // Greenland
    #[Label('Γκάμπια')]
    case GM = 'GM'; // Gambia
    #[Label('Γουινέα')]
    case GN = 'GN'; // Guinea
    #[Label('Γουαδελούπη')]
    case GP = 'GP'; // Guadeloupe
    #[Label('Ισημερινή Γουινέα')]
    case GQ = 'GQ'; // Equatorial Guinea
    #[Label('Ελλάδα')]
    case GR = 'GR'; // Greece
    #[Label('Νότια Γεωργία και Νότιες Νήσοι Σάντουιτς')]
    case GS = 'GS'; // South Georgia and the South Sandwich Islands
    #[Label('Γουατεμάλα')]
    case GT = 'GT'; // Guatemala
    #[Label('Γκουάμ')]
    case GU = 'GU'; // Guam
    #[Label('Γουινέα-Μπισάου')]
    case GW = 'GW'; // Guinea-Bissau
    #[Label('Γουιάνα')]
    case GY = 'GY'; // Guyana
    #[Label('Χονγκ Κονγκ')]
    case HK = 'HK'; // Hong Kong
    #[Label('Νήσος Χερντ και Νήσοι Μακντόναλντ')]
    case HM = 'HM'; // Heard Island and McDonald Islands
    #[Label('Ονδούρα')]
    case HN = 'HN'; // Honduras
    #[Label('Κροατία')]
    case HR = 'HR'; // Croatia
    #[Label('Αϊτή')]
    case HT = 'HT'; // Haiti
    #[Label('Ουγγαρία')]
    case HU = 'HU'; // Hungary
    #[Label('Ινδονησία')]
    case ID = 'ID'; // Indonesia
    #[Label('Ιρλανδία')]
    case IE = 'IE'; // Ireland
    #[Label('Ισραήλ')]
    case IL = 'IL'; // Israel
    #[Label('Νήσος του Μαν')]
    case IM = 'IM'; // Isle of Man
    #[Label('Ινδία')]
    case IN = 'IN'; // India
    #[Label('Βρετανικό Έδαφος Ινδικού Ωκεανού')]
    case IO = 'IO'; // British Indian Ocean Territory
    #[Label('Ιράκ')]
    case IQ = 'IQ'; // Iraq
    #[Label('Ιράν')]
    case IR = 'IR'; // Iran, Islamic Republic of
    #[Label('Ισλανδία')]
    case IS = 'IS'; // Iceland
    #[Label('Ιταλία')]
    case IT = 'IT'; // Italy
    #[Label('Τζέρσεϊ')]
    case JE = 'JE'; // Jersey
    #[Label('Τζαμάικα')]
    case JM = 'JM'; // Jamaica
    #[Label('Ιορδανία')]
    case JO = 'JO'; // Jordan
    #[Label('Ιαπωνία')]
    case JP = 'JP'; // Japan
    #[Label('Κένυα')]
    case KE = 'KE'; // Kenya
    #[Label('Κιργιζία')]
    case KG = 'KG'; // Kyrgyzstan
    #[Label('Καμπότζη')]
    case KH = 'KH'; // Cambodia
    #[Label('Κιριμπάτι')]
    case KI = 'KI'; // Kiribati
    #[Label('Κομόρες')]
    case KM = 'KM'; // Comoros
    #[Label('Άγιος Χριστόφορος και Νέβις')]
    case KN = 'KN'; // Saint Kitts and Nevis
    #[Label('Βόρεια Κορέα')]
    case KP = 'KP'; // Korea, Democratic People's Republic of
    #[Label('Νότια Κορέα')]
    case KR = 'KR'; // Korea, Republic of
    #[Label('Κουβέιτ')]
    case KW = 'KW'; // Kuwait
    #[Label('Νήσοι Κέιμαν')]
    case KY = 'KY'; // Cayman Islands
    #[Label('Καζακστάν')]
    case KZ = 'KZ'; // Kazakhstan
    #[Label('Λάος')]
    case LA = 'LA'; // Lao People's Democratic Republic
    #[Label('Λίβανος')]
    case LB = 'LB'; // Lebanon
    #[Label('Άγιος Λουκίας')]
    case LC = 'LC'; // Saint Lucia
    #[Label('Λιχτενστάιν')]
    case LI = 'LI'; // Liechtenstein
    #[Label('Σρι Λάνκα')]
    case LK = 'LK'; // Sri Lanka
    #[Label('Λιβερία')]
    case LR = 'LR'; // Liberia
    #[Label('Λεσότο')]
    case LS = 'LS'; // Lesotho
    #[Label('Λιθουανία')]
    case LT = 'LT'; // Lithuania
    #[Label('Λουξεμβούργο')]
    case LU = 'LU'; // Luxembourg
    #[Label('Λετονία')]
    case LV = 'LV'; // Latvia
    #[Label('Λιβύη')]
    case LY = 'LY'; // Libya
    #[Label('Μαρόκο')]
    case MA = 'MA'; // Morocco
    #[Label('Μονακό')]
    case MC = 'MC'; // Monaco
    #[Label('Μολδαβία')]
    case MD = 'MD'; // Moldova, Republic of
    #[Label('Μαυροβούνιο')]
    case ME = 'ME'; // Montenegro
    #[Label('Άγιος Μαρτίνος (Γαλλικό τμήμα)')]
    case MF = 'MF'; // Saint Martin (French part)
    #[Label('Μαδαγασκάρη')]
    case MG = 'MG'; // Madagascar
    #[Label('Νήσοι Μάρσαλ')]
    case MH = 'MH'; // Marshall Islands
    #[Label('Βόρεια Μακεδονία')]
    case MK = 'MK'; // North Macedonia
    #[Label('Μάλι')]
    case ML = 'ML'; // Mali
    #[Label('Μιανμάρ')]
    case MM = 'MM'; // Myanmar
    #[Label('Μογγολία')]
    case MN = 'MN'; // Mongolia
    #[Label('Μακάο')]
    case MO = 'MO'; // Macao
    #[Label('Βόρειες Μαριάνες Νήσοι')]
    case MP = 'MP'; // Northern Mariana Islands
    #[Label('Μαρτινίκα')]
    case MQ = 'MQ'; // Martinique
    #[Label('Μαυριτανία')]
    case MR = 'MR'; // Mauritania
    #[Label('Μοντσεράτ')]
    case MS = 'MS'; // Montserrat
    #[Label('Μάλτα')]
    case MT = 'MT'; // Malta
    #[Label('Μαυρίκιος')]
    case MU = 'MU'; // Mauritius
    #[Label('Μαλδίβες')]
    case MV = 'MV'; // Maldives
    #[Label('Μαλάουι')]
    case MW = 'MW'; // Malawi
    #[Label('Μεξικό')]
    case MX = 'MX'; // Mexico
    #[Label('Μαλαισία')]
    case MY = 'MY'; // Malaysia
    #[Label('Μοζαμβίκη')]
    case MZ = 'MZ'; // Mozambique
    #[Label('Ναμίμπια')]
    case NA = 'NA'; // Namibia
    #[Label('Νέα Καληδονία')]
    case NC = 'NC'; // New Caledonia
    #[Label('Νίγηρας')]
    case NE = 'NE'; // Niger
    #[Label('Νήσος Νόρφολκ')]
    case NF = 'NF'; // Norfolk Island
    #[Label('Νιγηρία')]
    case NG = 'NG'; // Nigeria
    #[Label('Νικαράγουα')]
    case NI = 'NI'; // Nicaragua
    #[Label('Ολλανδία')]
    case NL = 'NL'; // Netherlands
    #[Label('Νορβηγία')]
    case NO = 'NO'; // Norway
    #[Label('Νεπάλ')]
    case NP = 'NP'; // Nepal
    #[Label('Ναουρού')]
    case NR = 'NR'; // Nauru
    #[Label('Νιούε')]
    case NU = 'NU'; // Niue
    #[Label('Νέα Ζηλανδία')]
    case NZ = 'NZ'; // New Zealand
    #[Label('Ωκεανία')]
    case OC = 'OC'; // Oceania
    #[Label('Ομάν')]
    case OM = 'OM'; // Oman
    #[Label('Παναμάς')]
    case PA = 'PA'; // Panama
    #[Label('Περού')]
    case PE = 'PE'; // Peru
    #[Label('Γαλλική Πολυνησία')]
    case PF = 'PF'; // French Polynesia
    #[Label('Παπούα Νέα Γουινέα')]
    case PG = 'PG'; // Papua New Guinea
    #[Label('Φιλιππίνες')]
    case PH = 'PH'; // Philippines
    #[Label('Πακιστάν')]
    case PK = 'PK'; // Pakistan
    #[Label('Πολωνία')]
    case PL = 'PL'; // Poland
    #[Label('Σαιν Πιερ και Μικελόν')]
    case PM = 'PM'; // Saint Pierre and Miquelon
    #[Label('Νήσοι Πίτκαιρν')]
    case PN = 'PN'; // Pitcairn
    #[Label('Πουέρτο Ρίκο')]
    case PR = 'PR'; // Puerto Rico
    #[Label('Παλαιστίνη')]
    case PS = 'PS'; // Palestine, State of
    #[Label('Πορτογαλία')]
    case PT = 'PT'; // Portugal
    #[Label('Παλάου')]
    case PW = 'PW'; // Palau
    #[Label('Παραγουάη')]
    case PY = 'PY'; // Paraguay
    #[Label('Κατάρ')]
    case QA = 'QA'; // Qatar
    #[Label('Ρεϊνιόν')]
    case RE = 'RE'; // Réunion
    #[Label('Ρουμανία')]
    case RO = 'RO'; // Romania
    #[Label('Σερβία')]
    case RS = 'RS'; // Serbia
    #[Label('Ρωσία')]
    case RU = 'RU'; // Russian Federation
    #[Label('Ρουάντα')]
    case RW = 'RW'; // Rwanda
    #[Label('Σαουδική Αραβία')]
    case SA = 'SA'; // Saudi Arabia
    #[Label('Νήσοι Σολομώντος')]
    case SB = 'SB'; // Solomon Islands
    #[Label('Σεϋχέλλες')]
    case SC = 'SC'; // Seychelles
    #[Label('Σουδάν')]
    case SD = 'SD'; // Sudan
    #[Label('Σουηδία')]
    case SE = 'SE'; // Sweden
    #[Label('Σιγκαπούρη')]
    case SG = 'SG'; // Singapore
    #[Label('Νήσος Αγίας Ελένης')]
    case SH = 'SH'; // Saint Helena, Ascension and Tristan da Cunha
    #[Label('Σλοβενία')]
    case SI = 'SI'; // Slovenia
    #[Label('Σβάλμπαρντ και Γιαν Μαγιέν')]
    case SJ = 'SJ'; // Svalbard and Jan Mayen
    #[Label('Σλοβακία')]
    case SK = 'SK'; // Slovakia
    #[Label('Σιέρα Λεόνε')]
    case SL = 'SL'; // Sierra Leone
    #[Label('Άγιος Μαρίνος')]
    case SM = 'SM'; // San Marino
    #[Label('Σενεγάλη')]
    case SN = 'SN'; // Senegal
    #[Label('Σομαλία')]
    case SO = 'SO'; // Somalia
    #[Label('Σουρινάμ')]
    case SR = 'SR'; // Suriname
    #[Label('Σάο Τομέ και Πρίνσιπε')]
    case ST = 'ST'; // Sao Tome and Principe
    #[Label('Ελ Σαλβαδόρ')]
    case SV = 'SV'; // El Salvador
    #[Label('Συρία')]
    case SY = 'SY'; // Syrian Arab Republic
    #[Label('Σουαζιλάνδη')]
    case SZ = 'SZ'; // Eswatini
    #[Label('Νήσοι Τερκς και Κάικος')]
    case TC = 'TC'; // Turks and Caicos Islands
    #[Label('Τσαντ')]
    case TD = 'TD'; // Chad
    #[Label('Γαλλικά Νότια και Ανταρκτικά Εδάφη')]
    case TF = 'TF'; // French Southern Territories
    #[Label('Τόγκο')]
    case TG = 'TG'; // Togo
    #[Label('Ταϊλάνδη')]
    case TH = 'TH'; // Thailand
    #[Label('Τατζικιστάν')]
    case TJ = 'TJ'; // Tajikistan
    #[Label('Τοκελάου')]
    case TK = 'TK'; // Tokelau
    #[Label('Ανατολικό Τιμόρ')]
    case TL = 'TL'; // Timor-Leste
    #[Label('Τουρκμενιστάν')]
    case TM = 'TM'; // Turkmenistan
    #[Label('Τυνησία')]
    case TN = 'TN'; // Tunisia
    #[Label('Τόνγκα')]
    case TO = 'TO'; // Tonga
    #[Label('Τουρκία')]
    case TR = 'TR'; // Turkey
    #[Label('Τρινιντάντ και Τομπάγκο')]
    case TT = 'TT'; // Trinidad and Tobago
    #[Label('Τουβαλού')]
    case TV = 'TV'; // Tuvalu
    #[Label('Ταϊβάν')]
    case TW = 'TW'; // Taiwan, Province of China
    #[Label('Τανζανία')]
    case TZ = 'TZ'; // Tanzania, United Republic of
    #[Label('Ουκρανία')]
    case UA = 'UA'; // Ukraine
    #[Label('Ουγκάντα')]
    case UG = 'UG'; // Uganda
    #[Label('Απομακρυσμένες Νησίδες Ηνωμένων Πολιτειών')]
    case UM = 'UM'; // United States Minor Outlying Islands
    #[Label('Ηνωμένες Πολιτείες')]
    case US = 'US'; // United States
    #[Label('Ουρουγουάη')]
    case UY = 'UY'; // Uruguay
    #[Label('Ουζμπεκιστάν')]
    case UZ = 'UZ'; // Uzbekistan
    #[Label('Βατικανό')]
    case VA = 'VA'; // Holy See (Vatican City State)
    #[Label('Άγιος Βικέντιος και Γρεναδίνες')]
    case VC = 'VC'; // Saint Vincent and the Grenadines
    #[Label('Βενεζουέλα')]
    case VE = 'VE'; // Venezuela, Bolivarian Republic of
    #[Label('Βρετανικές Παρθένοι Νήσοι')]
    case VG = 'VG'; // Virgin Islands, British
    #[Label('Αμερικανικές Παρθένοι Νήσοι')]
    case VI = 'VI'; // Virgin Islands, U.S.
    #[Label('Βιετνάμ')]
    case VN = 'VN'; // Viet Nam
    #[Label('Βανουάτου')]
    case VU = 'VU'; // Vanuatu
    #[Label('Ουάλις και Φουτούνα')]
    case WF = 'WF'; // Wallis and Futuna
    #[Label('Σαμόα')]
    case WS = 'WS'; // Samoa
    #[Label('Υεμένη')]
    case YE = 'YE'; // Yemen
    #[Label('Μαγιότ')]
    case YT = 'YT'; // Mayotte
    #[Label('Νότια Αφρική')]
    case ZA = 'ZA'; // South Africa
    #[Label('Ζάμπια')]
    case ZM = 'ZM'; // Zambia
    #[Label('Ζιμπάμπουε')]
    case ZW = 'ZW'; // Zimbabwe

    public function isInEuropeanUnion(): bool
    {
        return in_array($this, self::europeanUnionCountries());
    }

    /**
     * @return list<self>
     */
    public static function europeanUnionCountries(): array
    {
        return [
            self::AT, // Austria
            self::BE, // Belgium
            self::BG, // Bulgaria
            self::HR, // Croatia
            self::CY, // Cyprus
            self::CZ, // Czech Republic
            self::DK, // Denmark
            self::EE, // Estonia
            self::FI, // Finland
            self::FR, // France
            self::DE, // Germany
            self::GR, // Greece
            self::HU, // Hungary
            self::IE, // Ireland
            self::IT, // Italy
            self::LV, // Latvia
            self::LT, // Lithuania
            self::LU, // Luxembourg
            self::MT, // Malta
            self::NL, // Netherlands
            self::PL, // Poland
            self::PT, // Portugal
            self::RO, // Romania
            self::SK, // Slovakia
            self::SI, // Slovenia
            self::ES, // Spain
            self::SE, // Sweden
        ];
    }
}
