<?php

namespace Firebed\AadeMyData\Types;

use Firebed\AadeMyData\Enums\HasLabels;

enum Country: string
{
    use HasLabels;

    case AD = "AD"; // Andorra
    case AE = "AE"; // United Arab Emirates
    case AF = "AF"; // Afghanistan
    case AG = "AG"; // Antigua and Barbuda
    case AI = "AI"; // Anguilla
    case AL = "AL"; // Albania
    case AM = "AM"; // Armenia
    case AN = "AN"; // Netherlands Antilles
    case AO = "AO"; // Angola
    case AQ = "AQ"; // Antarctica
    case AR = "AR"; // Argentina
    case AS = "AS"; // American Samoa
    case AT = "AT"; // Austria
    case AU = "AU"; // Australia
    case AW = "AW"; // Aruba
    case AX = "AX"; // Åland Islands
    case AZ = "AZ"; // Azerbaijan
    case BA = "BA"; // Bosnia and Herzegovina
    case BB = "BB"; // Barbados
    case BD = "BD"; // Bangladesh
    case BE = "BE"; // Belgium
    case BF = "BF"; // Burkina Faso
    case BG = "BG"; // Bulgaria
    case BH = "BH"; // Bahrain
    case BI = "BI"; // Burundi
    case BJ = "BJ"; // Benin
    case BL = "BL"; // Saint Barthélemy
    case BM = "BM"; // Bermuda
    case BN = "BN"; // Brunei Darussalam
    case BO = "BO"; // Bolivia
    case BR = "BR"; // Brazil
    case BS = "BS"; // Bahamas
    case BT = "BT"; // Bhutan
    case BV = "BV"; // Bouvet Island
    case BW = "BW"; // Botswana
    case BY = "BY"; // Belarus
    case BZ = "BZ"; // Belize
    case CA = "CA"; // Canada
    case CC = "CC"; // Cocos (Keeling) Islands
    case CD = "CD"; // Congo, Democratic Republic of the
    case CF = "CF"; // Central African Republic
    case CG = "CG"; // Congo
    case CH = "CH"; // Switzerland
    case CI = "CI"; // Côte d'Ivoire
    case CK = "CK"; // Cook Islands
    case CL = "CL"; // Chile
    case CM = "CM"; // Cameroon
    case CN = "CN"; // China
    case CO = "CO"; // Colombia
    case CR = "CR"; // Costa Rica
    case CU = "CU"; // Cuba
    case CV = "CV"; // Cape Verde
    case CX = "CX"; // Christmas Island
    case CY = "CY"; // Cyprus
    case CZ = "CZ"; // Czech Republic
    case DE = "DE"; // Germany
    case DJ = "DJ"; // Djibouti
    case DK = "DK"; // Denmark
    case DM = "DM"; // Dominica
    case DO = "DO"; // Dominican Republic
    case DZ = "DZ"; // Algeria
    case EC = "EC"; // Ecuador
    case EE = "EE"; // Estonia
    case EG = "EG"; // Egypt
    case EH = "EH"; // Western Sahara
    case ER = "ER"; // Eritrea
    case ES = "ES"; // Spain
    case ET = "ET"; // Ethiopia
    case FI = "FI"; // Finland
    case FJ = "FJ"; // Fiji
    case FK = "FK"; // Falkland Islands (Malvinas)
    case FM = "FM"; // Micronesia, Federated States of
    case FO = "FO"; // Faroe Islands
    case FR = "FR"; // France
    case GA = "GA"; // Gabon
    case GB = "GB"; // United Kingdom
    case GD = "GD"; // Grenada
    case GE = "GE"; // Georgia
    case GF = "GF"; // French Guiana
    case GG = "GG"; // Guernsey
    case GH = "GH"; // Ghana
    case GI = "GI"; // Gibraltar
    case GL = "GL"; // Greenland
    case GM = "GM"; // Gambia
    case GN = "GN"; // Guinea
    case GP = "GP"; // Guadeloupe
    case GQ = "GQ"; // Equatorial Guinea
    case GR = "GR"; // Greece
    case GS = "GS"; // South Georgia and the South Sandwich Islands
    case GT = "GT"; // Guatemala
    case GU = "GU"; // Guam
    case GW = "GW"; // Guinea-Bissau
    case GY = "GY"; // Guyana
    case HK = "HK"; // Hong Kong
    case HM = "HM"; // Heard Island and McDonald Islands
    case HN = "HN"; // Honduras
    case HR = "HR"; // Croatia
    case HT = "HT"; // Haiti
    case HU = "HU"; // Hungary
    case ID = "ID"; // Indonesia
    case IE = "IE"; // Ireland
    case IL = "IL"; // Israel
    case IM = "IM"; // Isle of Man
    case IN = "IN"; // India
    case IO = "IO"; // British Indian Ocean Territory
    case IQ = "IQ"; // Iraq
    case IR = "IR"; // Iran, Islamic Republic of
    case IS = "IS"; // Iceland
    case IT = "IT"; // Italy
    case JE = "JE"; // Jersey
    case JM = "JM"; // Jamaica
    case JO = "JO"; // Jordan
    case JP = "JP"; // Japan
    case KE = "KE"; // Kenya
    case KG = "KG"; // Kyrgyzstan
    case KH = "KH"; // Cambodia
    case KI = "KI"; // Kiribati
    case KM = "KM"; // Comoros
    case KN = "KN"; // Saint Kitts and Nevis
    case KP = "KP"; // Korea, Democratic People's Republic of
    case KR = "KR"; // Korea, Republic of
    case KW = "KW"; // Kuwait
    case KY = "KY"; // Cayman Islands
    case KZ = "KZ"; // Kazakhstan
    case LA = "LA"; // Lao People's Democratic Republic
    case LB = "LB"; // Lebanon
    case LC = "LC"; // Saint Lucia
    case LI = "LI"; // Liechtenstein
    case LK = "LK"; // Sri Lanka
    case LR = "LR"; // Liberia
    case LS = "LS"; // Lesotho
    case LT = "LT"; // Lithuania
    case LU = "LU"; // Luxembourg
    case LV = "LV"; // Latvia
    case LY = "LY"; // Libya
    case MA = "MA"; // Morocco
    case MC = "MC"; // Monaco
    case MD = "MD"; // Moldova, Republic of
    case ME = "ME"; // Montenegro
    case MF = "MF"; // Saint Martin (French part)
    case MG = "MG"; // Madagascar
    case MH = "MH"; // Marshall Islands
    case MK = "MK"; // North Macedonia
    case ML = "ML"; // Mali
    case MM = "MM"; // Myanmar
    case MN = "MN"; // Mongolia
    case MO = "MO"; // Macao
    case MP = "MP"; // Northern Mariana Islands
    case MQ = "MQ"; // Martinique
    case MR = "MR"; // Mauritania
    case MS = "MS"; // Montserrat
    case MT = "MT"; // Malta
    case MU = "MU"; // Mauritius
    case MV = "MV"; // Maldives
    case MW = "MW"; // Malawi
    case MX = "MX"; // Mexico
    case MY = "MY"; // Malaysia
    case MZ = "MZ"; // Mozambique
    case NA = "NA"; // Namibia
    case NC = "NC"; // New Caledonia
    case NE = "NE"; // Niger
    case NF = "NF"; // Norfolk Island
    case NG = "NG"; // Nigeria
    case NI = "NI"; // Nicaragua
    case NL = "NL"; // Netherlands
    case NO = "NO"; // Norway
    case NP = "NP"; // Nepal
    case NR = "NR"; // Nauru
    case NU = "NU"; // Niue
    case NZ = "NZ"; // New Zealand
    case OC = "OC"; // Oceania
    case OM = "OM"; // Oman
    case PA = "PA"; // Panama
    case PE = "PE"; // Peru
    case PF = "PF"; // French Polynesia
    case PG = "PG"; // Papua New Guinea
    case PH = "PH"; // Philippines
    case PK = "PK"; // Pakistan
    case PL = "PL"; // Poland
    case PM = "PM"; // Saint Pierre and Miquelon
    case PN = "PN"; // Pitcairn
    case PR = "PR"; // Puerto Rico
    case PS = "PS"; // Palestine, State of
    case PT = "PT"; // Portugal
    case PW = "PW"; // Palau
    case PY = "PY"; // Paraguay
    case QA = "QA"; // Qatar
    case RE = "RE"; // Réunion
    case RO = "RO"; // Romania
    case RS = "RS"; // Serbia
    case RU = "RU"; // Russian Federation
    case RW = "RW"; // Rwanda
    case SA = "SA"; // Saudi Arabia
    case SB = "SB"; // Solomon Islands
    case SC = "SC"; // Seychelles
    case SD = "SD"; // Sudan
    case SE = "SE"; // Sweden
    case SG = "SG"; // Singapore
    case SH = "SH"; // Saint Helena, Ascension and Tristan da Cunha
    case SI = "SI"; // Slovenia
    case SJ = "SJ"; // Svalbard and Jan Mayen
    case SK = "SK"; // Slovakia
    case SL = "SL"; // Sierra Leone
    case SM = "SM"; // San Marino
    case SN = "SN"; // Senegal
    case SO = "SO"; // Somalia
    case SR = "SR"; // Suriname
    case ST = "ST"; // Sao Tome and Principe
    case SV = "SV"; // El Salvador
    case SY = "SY"; // Syrian Arab Republic
    case SZ = "SZ"; // Eswatini
    case TC = "TC"; // Turks and Caicos Islands
    case TD = "TD"; // Chad
    case TF = "TF"; // French Southern Territories
    case TG = "TG"; // Togo
    case TH = "TH"; // Thailand
    case TJ = "TJ"; // Tajikistan
    case TK = "TK"; // Tokelau
    case TL = "TL"; // Timor-Leste
    case TM = "TM"; // Turkmenistan
    case TN = "TN"; // Tunisia
    case TO = "TO"; // Tonga
    case TR = "TR"; // Turkey
    case TT = "TT"; // Trinidad and Tobago
    case TV = "TV"; // Tuvalu
    case TW = "TW"; // Taiwan, Province of China
    case TZ = "TZ"; // Tanzania, United Republic of
    case UA = "UA"; // Ukraine
    case UG = "UG"; // Uganda
    case UM = "UM"; // United States Minor Outlying Islands
    case US = "US"; // United States
    case UY = "UY"; // Uruguay
    case UZ = "UZ"; // Uzbekistan
    case VA = "VA"; // Holy See (Vatican City State)
    case VC = "VC"; // Saint Vincent and the Grenadines
    case VE = "VE"; // Venezuela, Bolivarian Republic of
    case VG = "VG"; // Virgin Islands, British
    case VI = "VI"; // Virgin Islands, U.S.
    case VN = "VN"; // Viet Nam
    case VU = "VU"; // Vanuatu
    case WF = "WF"; // Wallis and Futuna
    case WS = "WS"; // Samoa
    case YE = "YE"; // Yemen
    case YT = "YT"; // Mayotte
    case ZA = "ZA"; // South Africa
    case ZM = "ZM"; // Zambia
    case ZW = "ZW"; // Zimbabwe

    public function label(): string
    {
        return match ($this) {
            self::AD => "Ανδόρα",
            self::AE => "Ηνωμένα Αραβικά Εμιράτα",
            self::AF => "Αφγανιστάν",
            self::AG => "Αντίγκουα και Μπαρμπούντα",
            self::AI => "Ανγκουίλα",
            self::AL => "Αλβανία",
            self::AM => "Αρμενία",
            self::AN => "Ολλανδικές Αντίλλες",
            self::AO => "Αγκόλα",
            self::AQ => "Ανταρκτική",
            self::AR => "Αργεντινή",
            self::AS => "Αμερικανική Σαμόα",
            self::AT => "Αυστρία",
            self::AU => "Αυστραλία",
            self::AW => "Αρούμπα",
            self::AX => "Νήσοι Όλαντ",
            self::AZ => "Αζερμπαϊτζάν",
            self::BA => "Βοσνία και Ερζεγοβίνη",
            self::BB => "Μπαρμπάντος",
            self::BD => "Μπαγκλαντές",
            self::BE => "Βέλγιο",
            self::BF => "Μπουρκίνα Φάσο",
            self::BG => "Βουλγαρία",
            self::BH => "Μπαχρέιν",
            self::BI => "Μπουρούντι",
            self::BJ => "Μπενίν",
            self::BL => "Άγιος Βαρθολομαίος",
            self::BM => "Βερμούδες",
            self::BN => "Μπρουνέι",
            self::BO => "Βολιβία",
            self::BR => "Βραζιλία",
            self::BS => "Μπαχάμες",
            self::BT => "Μπουτάν",
            self::BV => "Νήσος Μπουβέ",
            self::BW => "Μποτσουάνα",
            self::BY => "Λευκορωσία",
            self::BZ => "Μπελίζ",
            self::CA => "Καναδάς",
            self::CC => "Νήσοι Κόκος (Κίλινγκ)",
            self::CD => "Κονγκό (Λαϊκή Δημοκρατία του)",
            self::CF => "Κεντροαφρικανική Δημοκρατία",
            self::CG => "Κονγκό",
            self::CH => "Ελβετία",
            self::CI => "Ακτή Ελεφαντοστού",
            self::CK => "Νήσοι Κουκ",
            self::CL => "Χιλή",
            self::CM => "Καμερούν",
            self::CN => "Κίνα",
            self::CO => "Κολομβία",
            self::CR => "Κόστα Ρίκα",
            self::CU => "Κούβα",
            self::CV => "Πράσινο Ακρωτήριο",
            self::CX => "Νήσος των Χριστουγέννων",
            self::CY => "Κύπρος",
            self::CZ => "Τσεχία",
            default => $this->label_2(),
        };
    }

    private function label_2(): string
    {
        return match ($this) {
            self::DE => "Γερμανία",
            self::DJ => "Τζιμπουτί",
            self::DK => "Δανία",
            self::DM => "Ντομίνικα",
            self::DO => "Δομινικανή Δημοκρατία",
            self::DZ => "Αλγερία",
            self::EC => "Εκουαδόρ",
            self::EE => "Εσθονία",
            self::EG => "Αίγυπτος",
            self::EH => "Δυτική Σαχάρα",
            self::ER => "Ερυθραία",
            self::ES => "Ισπανία",
            self::ET => "Αιθιοπία",
            self::FI => "Φινλανδία",
            self::FJ => "Φίτζι",
            self::FK => "Νήσοι Φώκλαντ",
            self::FM => "Μικρονησία",
            self::FO => "Νήσοι Φερόες",
            self::FR => "Γαλλία",
            self::GA => "Γκαμπόν",
            self::GB => "Ηνωμένο Βασίλειο",
            self::GD => "Γρενάδα",
            self::GE => "Γεωργία",
            self::GF => "Γαλλική Γουιάνα",
            self::GG => "Γκέρνσεϊ",
            self::GH => "Γκάνα",
            self::GI => "Γιβραλτάρ",
            self::GL => "Γροιλανδία",
            self::GM => "Γκάμπια",
            self::GN => "Γουινέα",
            self::GP => "Γουαδελούπη",
            self::GQ => "Ισημερινή Γουινέα",
            self::GR => "Ελλάδα",
            self::GS => "Νότια Γεωργία και Νότιες Νήσοι Σάντουιτς",
            self::GT => "Γουατεμάλα",
            self::GU => "Γκουάμ",
            self::GW => "Γουινέα-Μπισάου",
            self::GY => "Γουιάνα",
            self::HK => "Χονγκ Κονγκ",
            self::HM => "Νήσος Χερντ και Νήσοι Μακντόναλντ",
            self::HN => "Ονδούρα",
            self::HR => "Κροατία",
            self::HT => "Αϊτή",
            self::HU => "Ουγγαρία",
            self::ID => "Ινδονησία",
            self::IE => "Ιρλανδία",
            self::IL => "Ισραήλ",
            self::IM => "Νήσος του Μαν",
            self::IN => "Ινδία",
            self::IO => "Βρετανικό Έδαφος Ινδικού Ωκεανού",
            self::IQ => "Ιράκ",
            self::IR => "Ιράν",
            self::IS => "Ισλανδία",
            self::IT => "Ιταλία",
            self::JE => "Τζέρσεϊ",
            self::JM => "Τζαμάικα",
            self::JO => "Ιορδανία",
            self::JP => "Ιαπωνία",
            default => $this->label_3(),
        };
    }

    private function label_3(): string
    {
        return match ($this) {
            self::KE => "Κένυα",
            self::KG => "Κιργιζία",
            self::KH => "Καμπότζη",
            self::KI => "Κιριμπάτι",
            self::KM => "Κομόρες",
            self::KN => "Άγιος Χριστόφορος και Νέβις",
            self::KP => "Βόρεια Κορέα",
            self::KR => "Νότια Κορέα",
            self::KW => "Κουβέιτ",
            self::KY => "Νήσοι Κέιμαν",
            self::KZ => "Καζακστάν",
            self::LA => "Λάος",
            self::LB => "Λίβανος",
            self::LC => "Άγιος Λουκίας",
            self::LI => "Λιχτενστάιν",
            self::LK => "Σρι Λάνκα",
            self::LR => "Λιβερία",
            self::LS => "Λεσότο",
            self::LT => "Λιθουανία",
            self::LU => "Λουξεμβούργο",
            self::LV => "Λετονία",
            self::LY => "Λιβύη",
            self::MA => "Μαρόκο",
            self::MC => "Μονακό",
            self::MD => "Μολδαβία",
            self::ME => "Μαυροβούνιο",
            self::MF => "Άγιος Μαρτίνος (Γαλλικό τμήμα)",
            self::MG => "Μαδαγασκάρη",
            self::MH => "Νήσοι Μάρσαλ",
            self::MK => "Βόρεια Μακεδονία",
            self::ML => "Μάλι",
            self::MM => "Μιανμάρ",
            self::MN => "Μογγολία",
            self::MO => "Μακάο",
            self::MP => "Βόρειες Μαριάνες Νήσοι",
            self::MQ => "Μαρτινίκα",
            self::MR => "Μαυριτανία",
            self::MS => "Μοντσεράτ",
            self::MT => "Μάλτα",
            self::MU => "Μαυρίκιος",
            self::MV => "Μαλδίβες",
            self::MW => "Μαλάουι",
            self::MX => "Μεξικό",
            self::MY => "Μαλαισία",
            self::MZ => "Μοζαμβίκη",
            self::NA => "Ναμίμπια",
            self::NC => "Νέα Καληδονία",
            self::NE => "Νίγηρας",
            self::NF => "Νήσος Νόρφολκ",
            self::NG => "Νιγηρία",
            self::NI => "Νικαράγουα",
            self::NL => "Ολλανδία",
            self::NO => "Νορβηγία",
            self::NP => "Νεπάλ",
            self::NR => "Ναουρού",
            self::NU => "Νιούε",
            self::NZ => "Νέα Ζηλανδία",
            self::OC => "Ωκεανία",
            self::OM => "Ομάν",
            self::PA => "Παναμάς",
            self::PE => "Περού",
            self::PF => "Γαλλική Πολυνησία",
            self::PG => "Παπούα Νέα Γουινέα",
            self::PH => "Φιλιππίνες",
            self::PK => "Πακιστάν",
            self::PL => "Πολωνία",
            self::PM => "Σαιν Πιερ και Μικελόν",
            self::PN => "Νήσοι Πίτκαιρν",
            self::PR => "Πουέρτο Ρίκο",
            self::PS => "Παλαιστίνη",
            self::PT => "Πορτογαλία",
            self::PW => "Παλάου",
            self::PY => "Παραγουάη",
            self::QA => "Κατάρ",
            self::RE => "Ρεϊνιόν",
            self::RO => "Ρουμανία",
            self::RS => "Σερβία",
            self::RU => "Ρωσία",
            self::RW => "Ρουάντα",
            default => $this->label_4(),
        };
    }

    private function label_4(): string
    {
        return match ($this) {
            self::SA => "Σαουδική Αραβία",
            self::SB => "Νήσοι Σολομώντος",
            self::SC => "Σεϋχέλλες",
            self::SD => "Σουδάν",
            self::SE => "Σουηδία",
            self::SG => "Σιγκαπούρη",
            self::SH => "Νήσος Αγίας Ελένης",
            self::SI => "Σλοβενία",
            self::SJ => "Σβάλμπαρντ και Γιαν Μαγιέν",
            self::SK => "Σλοβακία",
            self::SL => "Σιέρα Λεόνε",
            self::SM => "Άγιος Μαρίνος",
            self::SN => "Σενεγάλη",
            self::SO => "Σομαλία",
            self::SR => "Σουρινάμ",
            self::ST => "Σάο Τομέ και Πρίνσιπε",
            self::SV => "Ελ Σαλβαδόρ",
            self::SY => "Συρία",
            self::SZ => "Σουαζιλάνδη",
            self::TC => "Νήσοι Τερκς και Κάικος",
            self::TD => "Τσαντ",
            self::TF => "Γαλλικά Νότια και Ανταρκτικά Εδάφη",
            self::TG => "Τόγκο",
            self::TH => "Ταϊλάνδη",
            self::TJ => "Τατζικιστάν",
            self::TK => "Τοκελάου",
            self::TL => "Ανατολικό Τιμόρ",
            self::TM => "Τουρκμενιστάν",
            self::TN => "Τυνησία",
            self::TO => "Τόνγκα",
            self::TR => "Τουρκία",
            self::TT => "Τρινιντάντ και Τομπάγκο",
            self::TV => "Τουβαλού",
            self::TW => "Ταϊβάν",
            self::TZ => "Τανζανία",
            self::UA => "Ουκρανία",
            self::UG => "Ουγκάντα",
            self::UM => "Απομακρυσμένες Νησίδες Ηνωμένων Πολιτειών",
            self::US => "Ηνωμένες Πολιτείες",
            self::UY => "Ουρουγουάη",
            self::UZ => "Ουζμπεκιστάν",
            self::VA => "Βατικανό",
            self::VC => "Άγιος Βικέντιος και Γρεναδίνες",
            self::VE => "Βενεζουέλα",
            self::VG => "Βρετανικές Παρθένοι Νήσοι",
            self::VI => "Αμερικανικές Παρθένοι Νήσοι",
            self::VN => "Βιετνάμ",
            self::VU => "Βανουάτου",
            self::WF => "Ουάλις και Φουτούνα",
            self::WS => "Σαμόα",
            self::YE => "Υεμένη",
            self::YT => "Μαγιότ",
            self::ZA => "Νότια Αφρική",
            self::ZM => "Ζάμπια",
            self::ZW => "Ζιμπάμπουε",
        };
    }

    public function isInEuropeanUnion(): bool
    {
        return in_array($this, self::europeanUnionCountries());
    }

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