<?php 

return [
    'title' => 'CarePro Installer',
    'next' => 'Sljedeći korak',
    'back' => 'prijašnji',
    'finish' => 'Instalirati',
    'forms' => [
        'errorTitle' => 'Došlo je do sljedećih pogrešaka:',
    ],
    'welcome' => [
        'templateTitle' => 'Dobrodošli',
        'title' => 'CarePro Installer',
        'message' => 'Čarobnjak za jednostavnu instalaciju i postavljanje.',
        'next' => 'Provjerite zahtjeve',
    ],
    'requirements' => [
        'templateTitle' => 'Korak 1 | Zahtjevi poslužitelja',
        'title' => 'Zahtjevi poslužitelja',
        'next' => 'Provjerite dozvole',
    ],
    'permissions' => [
        'templateTitle' => 'Korak 2 | dozvole',
        'title' => 'dozvole',
        'next' => 'Konfiguriranje okruženja',
    ],
    'environment' => [
        'menu' => [
            'templateTitle' => 'Korak 3 | Postavke okruženja',
            'title' => 'Postavke okruženja',
            'desc' => 'Odaberite kako želite konfigurirati aplikaciju <code> .env </code>.',
            'wizard-button' => 'Postavljanje čarobnjaka obrasca',
            'classic-button' => 'Klasični uređivač teksta',
        ],
        'wizard' => [
            'templateTitle' => 'Korak 3 | Postavke za okoliš | Vođeni čarobnjak',
            'title' => 'Vođeni čarobnjak <code> .env </code>',
            'tabs' => [
                'environment' => 'Okoliš',
                'database' => 'Baza podataka',
                'application' => 'primjena',
            ],
            'form' => [
                'name_required' => 'Naziv okruženja je obavezan.',
                'app_name_label' => 'Naziv aplikacije',
                'app_name_placeholder' => 'Naziv aplikacije',
                'app_environment_label' => 'Okoliš aplikacije',
                'app_environment_label_local' => 'lokalne',
                'app_environment_label_developement' => 'Razvoj',
                'app_environment_label_qa' => 'qa',
                'app_environment_label_production' => 'Proizvodnja',
                'app_environment_label_other' => 'drugo',
                'app_environment_placeholder_other' => 'Unesite svoje okruženje ...',
                'app_debug_label' => 'Otklanjanje pogrešaka u aplikaciji',
                'app_debug_label_true' => 'Pravi',
                'app_debug_label_false' => 'lažan',
                'app_log_level_label' => 'Razina prijave aplikacije',
                'app_log_level_label_debug' => 'otkloniti neispravnost',
                'app_log_level_label_info' => 'Informacije',
                'app_log_level_label_notice' => 'obavijest',
                'app_log_level_label_warning' => 'upozorenje',
                'app_log_level_label_error' => 'greška',
                'app_log_level_label_critical' => 'kritično',
                'app_log_level_label_alert' => 'uzbuna',
                'app_log_level_label_emergency' => 'hitan',
                'app_url_label' => 'URL aplikacije',
                'app_url_placeholder' => 'URL aplikacije',
                'db_connection_failed' => 'Nije moguće povezivanje s bazom podataka.',
                'db_connection_label' => 'Spajanje baze podataka',
                'db_connection_label_mysql' => 'mySQL',
                'db_connection_label_sqlite' => 'SQLite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Domaćin baze podataka',
                'db_host_placeholder' => 'Domaćin baze podataka',
                'db_port_label' => 'Luka baze podataka',
                'db_port_placeholder' => 'Luka baze podataka',
                'db_name_label' => 'Naziv baze podataka',
                'db_name_placeholder' => 'Naziv baze podataka',
                'db_username_label' => 'Korisničko ime baze podataka',
                'db_username_placeholder' => 'Korisničko ime baze podataka',
                'db_password_label' => 'Lozinka baze podataka',
                'db_password_placeholder' => 'Lozinka baze podataka',
                'app_tabs' => [
                    'more_info' => 'Više informacija',
                    'broadcasting_title' => '',
                    'broadcasting_label' => 'Vozač emitiranja',
                    'broadcasting_placeholder' => 'Vozač emitiranja',
                    'cache_label' => 'Vozač predmemorije',
                    'cache_placeholder' => 'Vozač predmemorije',
                    'session_label' => 'Vozač sjednice',
                    'session_placeholder' => 'Vozač sjednice',
                    'queue_label' => 'Vozač reda',
                    'queue_placeholder' => 'Vozač reda',
                    'redis_label' => 'Redis vozač',
                    'redis_host' => 'Redis domaćin',
                    'redis_password' => 'Redis lozinka',
                    'redis_port' => 'Redis Port',
                    'mail_label' => 'pošta',
                    'mail_driver_label' => 'Vozač pošte',
                    'mail_driver_placeholder' => 'Vozač pošte',
                    'mail_host_label' => 'Domaćin pošte',
                    'mail_host_placeholder' => 'Domaćin pošte',
                    'mail_port_label' => 'Luka za poštu',
                    'mail_port_placeholder' => 'Luka za poštu',
                    'mail_username_label' => 'Korisničko ime za poštu',
                    'mail_username_placeholder' => 'Korisničko ime za poštu',
                    'mail_password_label' => 'Lozinka pošte',
                    'mail_password_placeholder' => 'Lozinka pošte',
                    'mail_encryption_label' => 'Šifriranje pošte',
                    'mail_encryption_placeholder' => 'Šifriranje pošte',
                    'pusher_label' => 'laktaroš',
                    'pusher_app_id_label' => 'ID aplikacije za guranje',
                    'pusher_app_id_palceholder' => 'ID aplikacije za guranje',
                    'pusher_app_key_label' => 'Potisni ključ aplikacije',
                    'pusher_app_key_palceholder' => 'Potisni ključ aplikacije',
                    'pusher_app_secret_label' => 'Pushher App Secret',
                    'pusher_app_secret_palceholder' => 'Pushher App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Postavljanje baze podataka',
                    'setup_application' => 'Aplikacija za postavljanje',
                    'install' => 'Instalirati',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Korak 3 | Postavke za okoliš | Klasični urednik',
            'title' => 'Klasični uređivač okoliša',
            'save' => 'Spremi .env',
            'back' => 'Upotrijebite čarobnjaka za obrasce',
            'install' => 'Spremite i instalirajte',
        ],
        'success' => 'Vaše postavke .env datoteke spremljene su.',
        'errors' => '.Env datoteku nije moguće spremiti. Kreirajte je ručno.',
    ],
    'install' => 'Instalirati',
    'installed' => [
        'success_log_message' => 'CarePro Installer uspješno je instaliran na',
    ],
    'final' => [
        'title' => 'Instalacija je gotova',
        'templateTitle' => 'Instalacija je gotova',
        'finished' => 'Aplikacija je uspješno instalirana.',
        'migration' => '',
        'console' => 'Izlaz konzole aplikacije:',
        'log' => 'Upis u instalacijski dnevnik:',
        'env' => 'Konačna .env datoteka:',
        'exit' => 'Kliknite ovdje za izlaz',
    ],
    'updater' => [
        'title' => 'CarePro Updater',
        'welcome' => [
            'title' => 'Dobrodošli u Ažuriranje',
            'message' => 'Dobrodošli u čarobnjaka za ažuriranje.',
        ],
        'overview' => [
            'title' => 'Pregled',
            'message' => 'Postoji 1 ažuriranje. Postoje: ažuriranja broja.',
            'install_updates' => 'Instalirajte ažuriranja',
        ],
        'final' => [
            'title' => 'gotov',
            'finished' => 'Baza podataka aplikacije uspješno je ažurirana.',
            'exit' => 'Kliknite ovdje za izlaz',
        ],
        'log' => [
            'success_message' => 'CarePro Installer uspješno je Ažurirano na',
        ],
    ],
];