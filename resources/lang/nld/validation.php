<?php 

return [
    'captcha' => 'Voer de juiste code in op de CAPTCHA-afbeelding',
    'accepted' => 'Het :attribute moet worden geaccepteerd.',
    'active_url' => 'Het :attribute is geen geldige URL.',
    'after' => 'Het :attribute moet een datum na :date zijn.',
    'after_or_equal' => 'Het :attribute moet een datum na of gelijk aan :date zijn.',
    'alpha' => 'Het :attribute mag alleen letters bevatten.',
    'alpha_dash' => 'Het :attribute mag alleen letters, cijfers, streepjes en onderstrepingstekens bevatten.',
    'alpha_num' => 'Het :attribute mag alleen letters en cijfers bevatten.',
    'array' => 'Het :attribute moet een array zijn.',
    'before' => 'Het :attribute moet een datum voor :date zijn.',
    'before_or_equal' => 'Het :attribute moet een datum voor of gelijk aan :date zijn.',
    'between' => [
        'numeric' => 'Het :attribute moet tussen :min en :max liggen.',
        'file' => 'Het :attribute moet tussen :min en :max kilobytes zijn.',
        'string' => 'Het :attribute moet tussen :min en :max tekens zijn.',
        'array' => 'Het :attribute moet tussen :min en :max items hebben.',
    ],
    'boolean' => 'Het :attribute veld moet waar of onwaar zijn.',
    'confirmed' => 'De :attribute bevestiging komt niet overeen.',
    'date' => 'Het :attribute is geen geldige datum.',
    'date_equals' => 'Het :attribute moet een datum zijn die gelijk is aan :date.',
    'date_format' => 'Het :attribute komt niet overeen met het formaat :format.',
    'different' => 'Het :attribute en :other moeten verschillend zijn.',
    'digits' => 'Het :attribute moet zijn :digits cijfers.',
    'digits_between' => 'Het :attribute moet tussen :min en :max cijfers zijn.',
    'dimensions' => 'Het :attribute heeft ongeldige afbeeldingsafmetingen.',
    'distinct' => 'Het :attribute veld heeft een dubbele waarde.',
    'email' => 'Het :attribute moet een geldig e-mailadres zijn.',
    'ends_with' => 'Het :attribute moet eindigen op een van de volgende: :values',
    'exists' => 'Het geselecteerde :attribute is ongeldig.',
    'file' => 'Het :attribute moet een bestand zijn.',
    'filled' => 'Het :attribute veld moet een waarde hebben.',
    'gt' => [
        'numeric' => 'Het :attribute moet groter zijn dan :value.',
        'file' => 'Het :attribute moet groter zijn dan :value kilobytes.',
        'string' => 'Het :attribute moet groter zijn dan :value karakters.',
        'array' => 'Het :attribute moet meer dan :value items hebben.',
    ],
    'gte' => [
        'numeric' => 'Het :attribute moet groter zijn dan of gelijk zijn aan :value.',
        'file' => 'Het :attribute moet groter zijn dan of gelijk zijn aan :value kilobytes.',
        'string' => 'Het :attribute moet groter zijn dan of gelijk zijn aan :value tekens.',
        'array' => 'Het :attribute must have :value items or more.',
    ],
    'image' => 'Het :attribute moet een afbeelding zijn.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'in_array' => 'Het :attribute veld bestaat niet in :other.',
    'integer' => 'Het :attribute moet een geheel getal zijn.',
    'ip' => 'Het :attribute moet een geldig IP-adres zijn.',
    'ipv4' => 'Het :attribute moet een geldig IPv4-adres zijn.',
    'ipv6' => 'Het :attribute moet een geldig IPv6-adres zijn.',
    'json' => 'Het :attribute moet een geldige JSON-reeks zijn.',
    'lt' => [
        'numeric' => 'Het :attribute moet kleiner zijn dan :value.',
        'file' => 'Het :attribute moet kleiner zijn dan :value kilobytes.',
        'string' => 'Het :attribute moet kleiner zijn dan :value tekens.',
        'array' => 'Het :attribute moet minder dan :value items hebben.',
    ],
    'lte' => [
        'numeric' => 'Het :attribute moet kleiner zijn dan of gelijk zijn aan :value.',
        'file' => 'Het :attribute moet kleiner zijn dan of gelijk zijn aan :value kilobytes.',
        'string' => 'Het :attribute moet kleiner zijn dan of gelijk zijn aan :value tekens.',
        'array' => 'Het :attribute mag niet meer dan :value items hebben.',
    ],
    'max' => [
        'numeric' => 'Het :attribute mag niet groter zijn dan :max.',
        'file' => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'string' => 'Het :attribute mag niet groter zijn dan :max karakters.',
        'array' => 'Het :attribute mag niet meer dan :max items hebben.',
    ],
    'mimes' => 'Het :attribute moet een bestand zijn van het type: :values.',
    'mimetypes' => 'Het :attribute moet een bestand zijn van het type: :values.',
    'min' => [
        'numeric' => 'Het :attribute moet minimaal :min.',
        'file' => 'Het :attribute moet ten minste :min kilobytes zijn.',
        'string' => 'Het :attribute moet minimaal :min tekens zijn.',
        'array' => 'Het :attribute moet ten minste :min items bevatten.',
    ],
    'not_in' => 'Het geselecteerde :attribute is ongeldig.',
    'not_regex' => 'Het :attribute formaat is ongeldig.',
    'numeric' => 'Het :attribute moet een nummer zijn.',
    'password' => 'Het wachtwoord is incorrect.',
    'present' => 'Het :attribute veld moet aanwezig zijn.',
    'regex' => 'Het :attribute formaat is ongeldig.',
    'required' => 'Het :attribute veld is verplicht.',
    'required_if' => 'Het :attribute veld is vereist wanneer :other is :value.',
    'required_unless' => 'Het :attribute veld is vereist tenzij :other is in :values.',
    'required_with' => 'Het :attribute veld is vereist wanneer :values aanwezig zijn.',
    'required_with_all' => 'Het :attribute veld is vereist wanneer :values aanwezig zijn.',
    'required_without' => 'Het :attribute veld is vereist wanneer :values niet aanwezig zijn.',
    'required_without_all' => 'Het :attribute veld is vereist wanneer geen van :values aanwezig zijn.',
    'same' => 'Het :attribute en :other moeten overeenkomen.',
    'size' => [
        'numeric' => 'Het :attribute moet :size zijn.',
        'file' => 'Het :attribute moet :size kilobytes zijn.',
        'string' => 'Het :attribute moet :size karakters zijn.',
        'array' => 'Het :attribute moet :size items bevatten.',
    ],
    'starts_with' => 'Het :attribute moet beginnen met een van de volgende: :values',
    'string' => 'Het :attribute moet een string zijn.',
    'timezone' => 'Het :attribute moet een geldige zone zijn.',
    'unique' => 'Het :attribute is al in gebruik.',
    'uploaded' => 'Het :attribute kan niet worden geüpload.',
    'url' => 'Het :attribute formaat is ongeldig.',
    'uuid' => 'Het :attribute moet een geldige UUID zijn.',
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'aangepast bericht',
        ],
    ],
    'attributes' => '',
];