<?php
require '../../config/config.php';
require '../../incl/main.php';

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    $response = [
        'success' => 'false',
        'response' => 'Unable to access database',
    ];

    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$apiKey = isset($_POST['key']) ? $_POST['key'] : null;
$session = isset($_COOKIE['session']) ? $_COOKIE['session'] : null;
$country = isset($_POST['country']) ? $_POST['country'] : null;
$timezone = isset($_POST['timezone']) ? $_POST['timezone'] : null;
$dateformat = isset($_POST['dateformat']) ? $_POST['dateformat'] : null;
$timeformat = isset($_POST['timeformat']) ? $_POST['timeformat'] : null;

$query = "UPDATE users SET ";
$params = [];
$paramTypes = "";

if ($country !== null) {
    $countries = ["Andorra", "French Southern and Antarctic Lands", "Laos", "Canada", "Nigeria", "Vanuatu", "Czechia", "Malawi", "Mali", "Iceland", "Norway", "Saint Vincent and the Grenadines", "Guadeloupe", "Chile", "Bermuda", "Kuwait", "Dominica", "Montenegro", "United States Virgin Islands", "Cameroon", "Sri Lanka", "China", "Bangladesh", "Sweden", "Grenada", "Turkey", "Guinea", "Tanzania", "Rwanda", "Singapore", "Morocco", "Saint Barthélemy", "Iraq", "Brunei", "Isle of Man", "North Korea", "Iran", "Curaçao", "Paraguay", "Albania", "Tajikistan", "Bolivia", "Austria", "Saint Kitts and Nevis", "United States Minor Outlying Islands", "Colombia", "Kosovo", "Belize", "Guinea-Bissau", "Marshall Islands", "Myanmar", "French Polynesia", "Brazil", "Croatia", "Somalia", "Afghanistan", "Anguilla", "Cook Islands", "Western Sahara", "New Zealand", "Eritrea", "Cambodia", "Bahamas", "Belarus", "Norfolk Island", "Tuvalu", "South Georgia", "Mauritania", "New Caledonia", "Bulgaria", "Mozambique", "Niue", "Estonia", "Italy", "Malta", "Slovenia", "India", "Peru", "Burundi", "Lithuania", "United States", "Honduras", "Tonga", "Saudi Arabia", "Suriname", "Qatar", "Saint Helena, Ascension and Tristan da Cunha", "Gibraltar", "Northern Mariana Islands", "Mauritius", "Barbados", "Réunion", "British Indian Ocean Territory", "Syria", "Egypt", "São Tomé and Príncipe", "Kiribati", "Timor-Leste", "Lesotho", "Solomon Islands", "Libya", "South Korea", "Liechtenstein", "Nicaragua", "Ecuador", "Maldives", "Algeria", "Kyrgyzstan", "Finland", "Antarctica", "Kenya", "Cuba", "Montserrat", "Poland", "Åland Islands", "Ethiopia", "Togo", "Bosnia and Herzegovina", "Uruguay", "Guam", "Cape Verde", "Chad", "Vatican City", "Palau", "Haiti", "Yemen", "Eswatini", "Zimbabwe", "Greece", "Israel", "Saint Martin", "Antigua and Barbuda", "Cyprus", "Sint Maarten", "Monaco", "Fiji", "Ukraine", "Martinique", "Hong Kong", "Portugal", "Bhutan", "Nepal", "France", "Ireland", "United Arab Emirates", "Guernsey", "Saint Lucia", "Dominican Republic", "Serbia", "Botswana", "Ivory Coast", "Ghana", "Comoros", "Azerbaijan", "United Kingdom", "Central African Republic", "Palestine", "Caribbean Netherlands", "Taiwan", "Pitcairn Islands", "San Marino", "Svalbard and Jan Mayen", "Djibouti", "Wallis and Futuna", "Denmark", "Papua New Guinea", "Madagascar", "Bouvet Island", "Hungary", "Tokelau", "Trinidad and Tobago", "Gambia", "Luxembourg", "Cocos (Keeling) Islands", "Republic of the Congo", "Argentina", "DR Congo", "Greenland", "Jordan", "Belgium", "Switzerland", "Indonesia", "Lebanon", "Malaysia", "Cayman Islands", "Slovakia", "Armenia", "Christmas Island", "Mongolia", "Saint Pierre and Miquelon", "Japan", "South Africa", "Philippines", "Micronesia", "Germany", "Latvia", "Jamaica", "Macau", "Nauru", "Faroe Islands", "Guyana", "Burkina Faso", "Sudan", "Russia", "Mayotte", "Australia", "Liberia", "Mexico", "Tunisia", "Aruba", "Kazakhstan", "Oman", "French Guiana", "Niger", "Turkmenistan", "Sierra Leone", "Samoa", "Senegal", "Georgia", "Namibia", "South Sudan", "Thailand", "Bahrain", "Heard Island and McDonald Islands", "Falkland Islands", "Jersey", "Vietnam", "Guatemala", "Moldova", "North Macedonia", "Uzbekistan", "Romania", "Uganda", "El Salvador", "Zambia", "Gabon", "Equatorial Guinea", "Spain", "Netherlands", "British Virgin Islands", "Benin", "Pakistan", "Panama", "Turks and Caicos Islands", "Angola", "American Samoa", "Venezuela", "Costa Rica", "Puerto Rico", "Seychelles"];
    if (!in_array($country, $countries)) {
        $country = null;
        die('{"success": false, "response": "Invalid country" }');
        return;
    }

    $query .= "country = ?, ";
    array_push($params, $country);
    $paramTypes .= "s";
}

if ($timezone !== null) {
    $timezones = ['Africa/Abidjan', 'Africa/Accra', 'Africa/Addis_Ababa', 'Africa/Algiers', 'Africa/Asmara', 'Africa/Asmera', 'Africa/Bamako', 'Africa/Bangui', 'Africa/Banjul', 'Africa/Bissau', 'Africa/Blantyre', 'Africa/Brazzaville', 'Africa/Bujumbura', 'Africa/Cairo', 'Africa/Casablanca', 'Africa/Ceuta', 'Africa/Conakry', 'Africa/Dakar', 'Africa/Dar_es_Salaam', 'Africa/Djibouti', 'Africa/Douala', 'Africa/El_Aaiun', 'Africa/Freetown', 'Africa/Gaborone', 'Africa/Harare', 'Africa/Johannesburg', 'Africa/Juba', 'Africa/Kampala', 'Africa/Khartoum', 'Africa/Kigali', 'Africa/Kinshasa', 'Africa/Lagos', 'Africa/Libreville', 'Africa/Lome', 'Africa/Luanda', 'Africa/Lubumbashi', 'Africa/Lusaka', 'Africa/Malabo', 'Africa/Maputo', 'Africa/Maseru', 'Africa/Mbabane', 'Africa/Mogadishu', 'Africa/Monrovia', 'Africa/Nairobi', 'Africa/Ndjamena', 'Africa/Niamey', 'Africa/Nouakchott', 'Africa/Ouagadougou', 'Africa/Porto-Novo', 'Africa/Sao_Tome', 'Africa/Timbuktu', 'Africa/Tripoli', 'Africa/Tunis', 'Africa/Windhoek', 'America/Adak', 'America/Anchorage', 'America/Anguilla', 'America/Antigua', 'America/Araguaina', 'America/Argentina/Buenos_Aires', 'America/Argentina/Catamarca', 'America/Argentina/ComodRivadavia', 'America/Argentina/Cordoba', 'America/Argentina/Jujuy', 'America/Argentina/La_Rioja', 'America/Argentina/Mendoza', 'America/Argentina/Rio_Gallegos', 'America/Argentina/Salta', 'America/Argentina/San_Juan', 'America/Argentina/San_Luis', 'America/Argentina/Tucuman', 'America/Argentina/Ushuaia', 'America/Aruba', 'America/Asuncion', 'America/Atikokan', 'America/Atka', 'America/Bahia', 'America/Bahia_Banderas', 'America/Barbados', 'America/Belem', 'America/Belize', 'America/Blanc-Sablon', 'America/Boa_Vista', 'America/Bogota', 'America/Boise', 'America/Buenos_Aires', 'America/Cambridge_Bay', 'America/Campo_Grande', 'America/Cancun', 'America/Caracas', 'America/Catamarca', 'America/Cayenne', 'America/Cayman', 'America/Chicago', 'America/Chihuahua', 'America/Ciudad_Juarez', 'America/Coral_Harbour', 'America/Cordoba', 'America/Costa_Rica', 'America/Creston', 'America/Cuiaba', 'America/Curacao', 'America/Danmarkshavn', 'America/Dawson', 'America/Dawson_Creek', 'America/Denver', 'America/Detroit', 'America/Dominica', 'America/Edmonton', 'America/Eirunepe', 'America/El_Salvador', 'America/Ensenada', 'America/Fort_Nelson', 'America/Fort_Wayne', 'America/Fortaleza', 'America/Glace_Bay', 'America/Godthab', 'America/Goose_Bay', 'America/Grand_Turk', 'America/Grenada', 'America/Guadeloupe', 'America/Guatemala', 'America/Guayaquil', 'America/Guyana', 'America/Halifax', 'America/Havana', 'America/Hermosillo', 'America/Indiana/Indianapolis', 'America/Indiana/Knox', 'America/Indiana/Marengo', 'America/Indiana/Petersburg', 'America/Indiana/Tell_City', 'America/Indiana/Vevay', 'America/Indiana/Vincennes', 'America/Indiana/Winamac', 'America/Indianapolis', 'America/Inuvik', 'America/Iqaluit', 'America/Jamaica', 'America/Jujuy', 'America/Juneau', 'America/Kentucky/Louisville', 'America/Kentucky/Monticello', 'America/Knox_IN', 'America/Kralendijk', 'America/La_Paz', 'America/Lima', 'America/Los_Angeles', 'America/Louisville', 'America/Lower_Princes', 'America/Maceio', 'America/Managua', 'America/Manaus', 'America/Marigot', 'America/Martinique', 'America/Matamoros', 'America/Mazatlan', 'America/Mendoza', 'America/Menominee', 'America/Merida', 'America/Metlakatla', 'America/Mexico_City', 'America/Miquelon', 'America/Moncton', 'America/Monterrey', 'America/Montevideo', 'America/Montreal', 'America/Montserrat', 'America/Nassau', 'America/New_York', 'America/Nipigon', 'America/Nome', 'America/Noronha', 'America/North_Dakota/Beulah', 'America/North_Dakota/Center', 'America/North_Dakota/New_Salem', 'America/Nuuk', 'America/Ojinaga', 'America/Panama', 'America/Pangnirtung', 'America/Paramaribo', 'America/Phoenix', 'America/Port-au-Prince', 'America/Port_of_Spain', 'America/Porto_Acre', 'America/Porto_Velho', 'America/Puerto_Rico', 'America/Punta_Arenas', 'America/Rainy_River', 'America/Rankin_Inlet', 'America/Recife', 'America/Regina', 'America/Resolute', 'America/Rio_Branco', 'America/Rosario', 'America/Santa_Isabel', 'America/Santarem', 'America/Santiago', 'America/Santo_Domingo', 'America/Sao_Paulo', 'America/Scoresbysund', 'America/Shiprock', 'America/Sitka', 'America/St_Barthelemy', 'America/St_Johns', 'America/St_Kitts', 'America/St_Lucia', 'America/St_Thomas', 'America/St_Vincent', 'America/Swift_Current', 'America/Tegucigalpa', 'America/Thule', 'America/Thunder_Bay', 'America/Tijuana', 'America/Toronto', 'America/Tortola', 'America/Vancouver', 'America/Virgin', 'America/Whitehorse', 'America/Winnipeg', 'America/Yakutat', 'America/Yellowknife', 'Antarctica/Casey', 'Antarctica/Davis', 'Antarctica/DumontDUrville', 'Antarctica/Macquarie', 'Antarctica/Mawson', 'Antarctica/McMurdo', 'Antarctica/Palmer', 'Antarctica/Rothera', 'Antarctica/South_Pole', 'Antarctica/Syowa', 'Antarctica/Troll', 'Antarctica/Vostok', 'Arctic/Longyearbyen', 'Asia/Aden', 'Asia/Almaty', 'Asia/Amman', 'Asia/Anadyr', 'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Ashgabat', 'Asia/Ashkhabad', 'Asia/Atyrau', 'Asia/Baghdad', 'Asia/Bahrain', 'Asia/Baku', 'Asia/Bangkok', 'Asia/Barnaul', 'Asia/Beirut', 'Asia/Bishkek', 'Asia/Brunei', 'Asia/Calcutta', 'Asia/Chita', 'Asia/Choibalsan', 'Asia/Chongqing', 'Asia/Chungking', 'Asia/Colombo', 'Asia/Dacca', 'Asia/Damascus', 'Asia/Dhaka', 'Asia/Dili', 'Asia/Dubai', 'Asia/Dushanbe', 'Asia/Famagusta', 'Asia/Gaza', 'Asia/Harbin', 'Asia/Hebron', 'Asia/Ho_Chi_Minh', 'Asia/Hong_Kong', 'Asia/Hovd', 'Asia/Irkutsk', 'Asia/Istanbul', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Jerusalem', 'Asia/Kabul', 'Asia/Kamchatka', 'Asia/Karachi', 'Asia/Kashgar', 'Asia/Kathmandu', 'Asia/Katmandu', 'Asia/Khandyga', 'Asia/Kolkata', 'Asia/Krasnoyarsk', 'Asia/Kuala_Lumpur', 'Asia/Kuching', 'Asia/Kuwait', 'Asia/Macao', 'Asia/Macau', 'Asia/Magadan', 'Asia/Makassar', 'Asia/Manila', 'Asia/Muscat', 'Asia/Nicosia', 'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Oral', 'Asia/Phnom_Penh', 'Asia/Pontianak', 'Asia/Pyongyang', 'Asia/Qatar', 'Asia/Qostanay', 'Asia/Qyzylorda', 'Asia/Rangoon', 'Asia/Riyadh', 'Asia/Saigon', 'Asia/Sakhalin', 'Asia/Samarkand', 'Asia/Seoul', 'Asia/Shanghai', 'Asia/Singapore', 'Asia/Srednekolymsk', 'Asia/Taipei', 'Asia/Tashkent', 'Asia/Tbilisi', 'Asia/Tehran', 'Asia/Tel_Aviv', 'Asia/Thimbu', 'Asia/Thimphu', 'Asia/Tokyo', 'Asia/Tomsk', 'Asia/Ujung_Pandang', 'Asia/Ulaanbaatar', 'Asia/Ulan_Bator', 'Asia/Urumqi', 'Asia/Ust-Nera', 'Asia/Vientiane', 'Asia/Vladivostok', 'Asia/Yakutsk', 'Asia/Yangon', 'Asia/Yekaterinburg', 'Asia/Yerevan', 'Atlantic/Azores', 'Atlantic/Bermuda', 'Atlantic/Canary', 'Atlantic/Cape_Verde', 'Atlantic/Faeroe', 'Atlantic/Faroe', 'Atlantic/Jan_Mayen', 'Atlantic/Madeira', 'Atlantic/Reykjavik', 'Atlantic/South_Georgia', 'Atlantic/St_Helena', 'Atlantic/Stanley', 'Australia/ACT', 'Australia/Adelaide', 'Australia/Brisbane', 'Australia/Broken_Hill', 'Australia/Canberra', 'Australia/Currie', 'Australia/Darwin', 'Australia/Eucla', 'Australia/Hobart', 'Australia/LHI', 'Australia/Lindeman', 'Australia/Lord_Howe', 'Australia/Melbourne', 'Australia/NSW', 'Australia/North', 'Australia/Perth', 'Australia/Queensland', 'Australia/South', 'Australia/Sydney', 'Australia/Tasmania', 'Australia/Victoria', 'Australia/West', 'Australia/Yancowinna', 'Brazil/Acre', 'Brazil/DeNoronha', 'Brazil/East', 'Brazil/West', 'CET', 'CST6CDT', 'Canada/Atlantic', 'Canada/Central', 'Canada/Eastern', 'Canada/Mountain', 'Canada/Newfoundland', 'Canada/Pacific', 'Canada/Saskatchewan', 'Canada/Yukon', 'Chile/Continental', 'Chile/EasterIsland', 'Cuba', 'EET', 'EST', 'EST5EDT', 'Egypt', 'Eire', 'Etc/GMT', 'Etc/GMT+0', 'Etc/GMT+1', 'Etc/GMT+10', 'Etc/GMT+11', 'Etc/GMT+12', 'Etc/GMT+2', 'Etc/GMT+3', 'Etc/GMT+4', 'Etc/GMT+5', 'Etc/GMT+6', 'Etc/GMT+7', 'Etc/GMT+8', 'Etc/GMT+9', 'Etc/GMT-0', 'Etc/GMT-1', 'Etc/GMT-10', 'Etc/GMT-11', 'Etc/GMT-12', 'Etc/GMT-13', 'Etc/GMT-14', 'Etc/GMT-2', 'Etc/GMT-3', 'Etc/GMT-4', 'Etc/GMT-5', 'Etc/GMT-6', 'Etc/GMT-7', 'Etc/GMT-8', 'Etc/GMT-9', 'Etc/GMT0', 'Etc/Greenwich', 'Etc/UCT', 'Etc/UTC', 'Etc/Universal', 'Etc/Zulu', 'Europe/Amsterdam', 'Europe/Andorra', 'Europe/Astrakhan', 'Europe/Athens', 'Europe/Belfast', 'Europe/Belgrade', 'Europe/Berlin', 'Europe/Bratislava', 'Europe/Brussels', 'Europe/Bucharest', 'Europe/Budapest', 'Europe/Busingen', 'Europe/Chisinau', 'Europe/Copenhagen', 'Europe/Dublin', 'Europe/Gibraltar', 'Europe/Guernsey', 'Europe/Helsinki', 'Europe/Isle_of_Man', 'Europe/Istanbul', 'Europe/Jersey', 'Europe/Kaliningrad', 'Europe/Kiev', 'Europe/Kirov', 'Europe/Kyiv', 'Europe/Lisbon', 'Europe/Ljubljana', 'Europe/London', 'Europe/Luxembourg', 'Europe/Madrid', 'Europe/Malta', 'Europe/Mariehamn', 'Europe/Minsk', 'Europe/Monaco', 'Europe/Moscow', 'Europe/Nicosia', 'Europe/Oslo', 'Europe/Paris', 'Europe/Podgorica', 'Europe/Prague', 'Europe/Riga', 'Europe/Rome', 'Europe/Samara', 'Europe/San_Marino', 'Europe/Sarajevo', 'Europe/Saratov', 'Europe/Simferopol', 'Europe/Skopje', 'Europe/Sofia', 'Europe/Stockholm', 'Europe/Tallinn', 'Europe/Tirane', 'Europe/Tiraspol', 'Europe/Ulyanovsk', 'Europe/Uzhgorod', 'Europe/Vaduz', 'Europe/Vatican', 'Europe/Vienna', 'Europe/Vilnius', 'Europe/Volgograd', 'Europe/Warsaw', 'Europe/Zagreb', 'Europe/Zaporozhye', 'Europe/Zurich', 'GB', 'GB-Eire', 'GMT', 'GMT+0', 'GMT-0', 'GMT0', 'Greenwich', 'HST', 'Hongkong', 'Iceland', 'Indian/Antananarivo', 'Indian/Chagos', 'Indian/Christmas', 'Indian/Cocos', 'Indian/Comoro', 'Indian/Kerguelen', 'Indian/Mahe', 'Indian/Maldives', 'Indian/Mauritius', 'Indian/Mayotte', 'Indian/Reunion', 'Iran', 'Israel', 'Jamaica', 'Japan', 'Kwajalein', 'Libya', 'MET', 'MST', 'MST7MDT', 'Mexico/BajaNorte', 'Mexico/BajaSur', 'Mexico/General', 'NZ', 'NZ-CHAT', 'Navajo', 'PRC', 'PST8PDT', 'Pacific/Apia', 'Pacific/Auckland', 'Pacific/Bougainville', 'Pacific/Chatham', 'Pacific/Chuuk', 'Pacific/Easter', 'Pacific/Efate', 'Pacific/Enderbury', 'Pacific/Fakaofo', 'Pacific/Fiji', 'Pacific/Funafuti', 'Pacific/Galapagos', 'Pacific/Gambier', 'Pacific/Guadalcanal', 'Pacific/Guam', 'Pacific/Honolulu', 'Pacific/Johnston', 'Pacific/Kanton', 'Pacific/Kiritimati', 'Pacific/Kosrae', 'Pacific/Kwajalein', 'Pacific/Majuro', 'Pacific/Marquesas', 'Pacific/Midway', 'Pacific/Nauru', 'Pacific/Niue', 'Pacific/Norfolk', 'Pacific/Noumea', 'Pacific/Pago_Pago', 'Pacific/Palau', 'Pacific/Pitcairn', 'Pacific/Pohnpei', 'Pacific/Ponape', 'Pacific/Port_Moresby', 'Pacific/Rarotonga', 'Pacific/Saipan', 'Pacific/Samoa', 'Pacific/Tahiti', 'Pacific/Tarawa', 'Pacific/Tongatapu', 'Pacific/Truk', 'Pacific/Wake', 'Pacific/Wallis', 'Pacific/Yap', 'Poland', 'Portugal', 'ROC', 'ROK', 'Singapore', 'Turkey', 'UCT', 'US/Alaska', 'US/Aleutian', 'US/Arizona', 'US/Central', 'US/East-Indiana', 'US/Eastern', 'US/Hawaii', 'US/Indiana-Starke', 'US/Michigan', 'US/Mountain', 'US/Pacific', 'US/Samoa', 'UTC', 'Universal', 'W-SU', 'WET', 'Zulu'];
    if (!in_array($timezone, $timezones)) {
        $timezone = null;
        return;
    }

    $query .= "timezone = ?, ";
    array_push($params, $timezone);
    $paramTypes .= "s";
}

if ($dateformat !== null) {
    if ($dateformat !== "mmddyyyy" && $dateformat !== "ddmmyyyy") {
        $dateformat = null;
        return;
    }

    $query .= "dateformat = ?, ";
    array_push($params, $dateformat);
    $paramTypes .= "s";
}

if ($timeformat !== null) {
    if ($timeformat !== "12hour" && $timeformat !== "24hour") {
        $timeformat = null;
        return;
    }

    $query .= "timeformat = ?, ";
    array_push($params, str_replace('hour', '', $timeformat));
    $paramTypes .= "s";
}

if ($country !== null || $timezone !== null || $dateformat !== null || $timeformat !== null) {
    $query = rtrim($query, ", ");
    if ($apiKey) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE api_key = ?");
        $stmt->bind_param("s", $apiKey);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_array(MYSQLI_NUM)[0];

        if ($count == 0) {
            $response = [
                'success' => 'false',
                'response' => 'Invalid API Key',
            ];

            http_response_code(401);
            header('Content-Type: application/json');
            die(json_encode($response));
        }
        $query .= " WHERE api_key = ?";
        array_push($params, $apiKey);
    } else if ($_COOKIE['session']) {
        $query .= " WHERE session = ?";
        array_push($params, $_COOKIE['session']);
    } else {
        $response = [
            'success' => 'false',
            'response' => 'Invalid API Key',
        ];
    
        http_response_code(401);
        header('Content-Type: application/json');
        die(json_encode($response));
    }
    $paramTypes .= "s";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramTypes, ...$params);
} else {
    $response = [
        'success' => 'false',
        'response' => 'No data provided',
    ];

    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode($response));
}

if ($stmt->execute()) {
    $response = [
        'success' => 'true',
        'response' => 'Saved preferences',
    ];

    http_response_code(200);
    header('Content-Type: application/json');
    die(json_encode($response));
} else {
    $response = [
        'success' => 'false',
        'response' => 'Error updating database',
    ];

    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode($response));
}
$stmt->close();

$stmt->close();
$conn->close();