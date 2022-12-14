<?php 

return [
    'title' => 'CarePro paigaldaja',
    'next' => 'Järgmine samm',
    'back' => 'Eelmine',
    'finish' => 'Installi',
    'forms' => [
        'errorTitle' => 'Ilmnesid järgmised vead:',
    ],
    'welcome' => [
        'templateTitle' => 'Tere tulemast',
        'title' => 'CarePro paigaldaja',
        'message' => 'Lihtne paigaldus- ja häälestusviisard.',
        'next' => 'Kontrollige nõudeid',
    ],
    'requirements' => [
        'templateTitle' => '1. samm | Nõuded serverile',
        'title' => 'Nõuded serverile',
        'next' => 'Kontrollige õigusi',
    ],
    'permissions' => [
        'templateTitle' => '2. samm | Load',
        'title' => 'Load',
        'next' => 'Keskkonna seadistamine',
    ],
    'environment' => [
        'menu' => [
            'templateTitle' => '3. samm | Keskkonnasätted',
            'title' => 'Keskkonnasätted',
            'desc' => 'Valige, kuidas soovite konfigureerida rakenduste faili <code> .env </code>.',
            'wizard-button' => 'Vormiviisardi seadistamine',
            'classic-button' => 'Klassikaline tekstiredaktor',
        ],
        'wizard' => [
            'templateTitle' => '3. samm | Keskkonnasätted | Juhendatud võlur',
            'title' => 'Juhendatud <code> .env </code> viisard',
            'tabs' => [
                'environment' => 'Keskkond',
                'database' => 'Andmebaas',
                'application' => 'Rakendus',
            ],
            'form' => [
                'name_required' => 'Keskkonna nimi on kohustuslik.',
                'app_name_label' => 'Rakenduse nimi',
                'app_name_placeholder' => 'Rakenduse nimi',
                'app_environment_label' => 'Rakenduskeskkond',
                'app_environment_label_local' => 'Kohalikud',
                'app_environment_label_developement' => 'Areng',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Tootmine',
                'app_environment_label_other' => 'Muud',
                'app_environment_placeholder_other' => 'Sisestage oma keskkonda ...',
                'app_debug_label' => 'Rakenduse silumine',
                'app_debug_label_true' => 'Tõsi',
                'app_debug_label_false' => 'Vale',
                'app_log_level_label' => 'Rakenduse logi tase',
                'app_log_level_label_debug' => 'silumine',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'teade',
                'app_log_level_label_warning' => 'hoiatus',
                'app_log_level_label_error' => 'viga',
                'app_log_level_label_critical' => 'kriitiline',
                'app_log_level_label_alert' => 'valvel',
                'app_log_level_label_emergency' => 'hädaolukord',
                'app_url_label' => 'Rakenduse URL',
                'app_url_placeholder' => 'Rakenduse URL',
                'db_connection_failed' => 'Andmebaasiga ei saanud ühendust.',
                'db_connection_label' => 'Andmebaasi ühendus',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Andmebaasi host',
                'db_host_placeholder' => 'Andmebaasi host',
                'db_port_label' => 'Andmebaasi port',
                'db_port_placeholder' => 'Andmebaasi port',
                'db_name_label' => 'Andmebaasi nimi',
                'db_name_placeholder' => 'Andmebaasi nimi',
                'db_username_label' => 'Andmebaasi kasutajanimi',
                'db_username_placeholder' => 'Andmebaasi kasutajanimi',
                'db_password_label' => 'Andmebaasi parool',
                'db_password_placeholder' => 'Andmebaasi parool',
                'app_tabs' => [
                    'more_info' => 'Rohkem infot',
                    'broadcasting_title' => '',
                    'broadcasting_label' => 'Saatejuht',
                    'broadcasting_placeholder' => 'Saatejuht',
                    'cache_label' => 'Vahemälu draiver',
                    'cache_placeholder' => 'Vahemälu draiver',
                    'session_label' => 'Seansi draiver',
                    'session_placeholder' => 'Seansi draiver',
                    'queue_label' => 'Järjekorra draiver',
                    'queue_placeholder' => 'Järjekorra draiver',
                    'redis_label' => 'Redise autojuht',
                    'redis_host' => 'Redis peremees',
                    'redis_password' => 'Redis parool',
                    'redis_port' => 'Redise sadam',
                    'mail_label' => 'Mail',
                    'mail_driver_label' => 'Posti draiver',
                    'mail_driver_placeholder' => 'Posti draiver',
                    'mail_host_label' => 'E-posti host',
                    'mail_host_placeholder' => 'E-posti host',
                    'mail_port_label' => 'E-post',
                    'mail_port_placeholder' => 'E-post',
                    'mail_username_label' => 'E-posti kasutajanimi',
                    'mail_username_placeholder' => 'E-posti kasutajanimi',
                    'mail_password_label' => 'E-posti parool',
                    'mail_password_placeholder' => 'E-posti parool',
                    'mail_encryption_label' => 'E-kirjade krüptimine',
                    'mail_encryption_placeholder' => 'E-kirjade krüptimine',
                    'pusher_label' => 'Tõukur',
                    'pusher_app_id_label' => 'Pusheri rakenduse ID',
                    'pusher_app_id_palceholder' => 'Pusheri rakenduse ID',
                    'pusher_app_key_label' => 'Tõukuri rakenduse võti',
                    'pusher_app_key_palceholder' => 'Tõukuri rakenduse võti',
                    'pusher_app_secret_label' => 'Pusheri rakenduse saladus',
                    'pusher_app_secret_palceholder' => 'Pusheri rakenduse saladus',
                ],
                'buttons' => [
                    'setup_database' => 'Seadistamise andmebaas',
                    'setup_application' => 'Häälestusrakendus',
                    'install' => 'Installi',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => '3. samm | Keskkonnasätted | Klassikaline toimetaja',
            'title' => 'Klassikaline keskkonnatoimetaja',
            'save' => 'Salvestage .env',
            'back' => 'Kasutage vormiviisardit',
            'install' => 'Salvestage ja installige',
        ],
        'success' => 'Teie .env-faili seaded on salvestatud.',
        'errors' => '.Vv-faili ei saa salvestada. Looge see käsitsi.',
    ],
    'install' => 'Installi',
    'installed' => [
        'success_log_message' => 'Installige CarePro Installer edukalt',
    ],
    'final' => [
        'title' => 'Installimine on lõpetatud',
        'templateTitle' => 'Installimine on lõpetatud',
        'finished' => 'Rakendus on edukalt installitud.',
        'migration' => '',
        'console' => 'Rakenduskonsooli väljund:',
        'log' => 'Paigaldamise logi kanne:',
        'env' => 'Lõplik .env-fail:',
        'exit' => 'Väljumiseks klõpsake siin',
    ],
    'updater' => [
        'title' => 'CarePro värskendaja',
        'welcome' => [
            'title' => 'Tere tulemast Updaterisse',
            'message' => 'Tere tulemast värskendusviisardisse.',
        ],
        'overview' => [
            'title' => 'Ülevaade',
            'message' => 'Värskendusi on 1. | Seal on: numbrivärskendusi.',
            'install_updates' => 'Installi värskendused',
        ],
        'final' => [
            'title' => 'Valmis',
            'finished' => 'Rakenduse andmebaasi on edukalt värskendatud.',
            'exit' => 'Väljumiseks klõpsake siin',
        ],
        'log' => [
            'success_message' => 'CarePro Installeri värskendamine õnnestus',
        ],
    ],
];