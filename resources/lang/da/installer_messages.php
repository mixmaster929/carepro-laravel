<?php 

return [
    'title' => 'CarePro-installationsprogram',
    'next' => 'Næste skridt',
    'back' => 'Tidligere',
    'finish' => 'Installere',
    'forms' => [
        'errorTitle' => 'Følgende fejl opstod:',
    ],
    'welcome' => [
        'templateTitle' => 'Velkommen',
        'title' => 'CarePro-installationsprogram',
        'message' => 'Nem installations- og installationsguide.',
        'next' => 'Kontroller krav',
    ],
    'requirements' => [
        'templateTitle' => 'Trin 1 | Serverkrav',
        'title' => 'Serverkrav',
        'next' => 'Kontroller tilladelser',
    ],
    'permissions' => [
        'templateTitle' => 'Trin 2 | Tilladelser',
        'title' => 'Tilladelser',
        'next' => 'Konfigurer miljø',
    ],
    'environment' => [
        'menu' => [
            'templateTitle' => 'Trin 3 | Miljøindstillinger',
            'title' => 'Miljøindstillinger',
            'desc' => 'Vælg, hvordan du vil konfigurere apps <code> .env </code> -filen.',
            'wizard-button' => 'Opsætning af formularguide',
            'classic-button' => 'Klassisk teksteditor',
        ],
        'wizard' => [
            'templateTitle' => 'Trin 3 | Miljøindstillinger | Guidet guide',
            'title' => 'Guidet <kode> .env </code> guiden',
            'tabs' => [
                'environment' => 'Miljø',
                'database' => 'Database',
                'application' => 'Ansøgning',
            ],
            'form' => [
                'name_required' => 'Der kræves et miljønavn.',
                'app_name_label' => 'App-navn',
                'app_name_placeholder' => 'App-navn',
                'app_environment_label' => 'App-miljø',
                'app_environment_label_local' => 'Lokal',
                'app_environment_label_developement' => 'Udvikling',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Produktion',
                'app_environment_label_other' => 'Andet',
                'app_environment_placeholder_other' => 'Indtast dit miljø ...',
                'app_debug_label' => 'App-fejlfinding',
                'app_debug_label_true' => 'Rigtigt',
                'app_debug_label_false' => 'Falsk',
                'app_log_level_label' => 'App-logniveau',
                'app_log_level_label_debug' => 'fejlfinde',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'varsel',
                'app_log_level_label_warning' => 'advarsel',
                'app_log_level_label_error' => 'fejl',
                'app_log_level_label_critical' => 'kritisk',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'nødsituation',
                'app_url_label' => 'App-adresse',
                'app_url_placeholder' => 'App-adresse',
                'db_connection_failed' => 'Kunne ikke oprette forbindelse til databasen.',
                'db_connection_label' => 'Databaseforbindelse',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Database vært',
                'db_host_placeholder' => 'Database vært',
                'db_port_label' => 'Database Port',
                'db_port_placeholder' => 'Database Port',
                'db_name_label' => 'Databasens navn',
                'db_name_placeholder' => 'Databasens navn',
                'db_username_label' => 'Databasebrugernavn',
                'db_username_placeholder' => 'Databasebrugernavn',
                'db_password_label' => 'Database adgangskode',
                'db_password_placeholder' => 'Database adgangskode',
                'app_tabs' => [
                    'more_info' => 'Mere info',
                    'broadcasting_title' => '',
                    'broadcasting_label' => 'Broadcast Driver',
                    'broadcasting_placeholder' => 'Broadcast Driver',
                    'cache_label' => 'Cache-driver',
                    'cache_placeholder' => 'Cache-driver',
                    'session_label' => 'Session driver',
                    'session_placeholder' => 'Session driver',
                    'queue_label' => 'Kø driver',
                    'queue_placeholder' => 'Kø driver',
                    'redis_label' => 'Redis Driver',
                    'redis_host' => 'Redis vært',
                    'redis_password' => 'Gentag adgangskode',
                    'redis_port' => 'Redis Port',
                    'mail_label' => 'Post',
                    'mail_driver_label' => 'Mail Driver',
                    'mail_driver_placeholder' => 'Mail Driver',
                    'mail_host_label' => 'Mailhost',
                    'mail_host_placeholder' => 'Mailhost',
                    'mail_port_label' => 'Mail Port',
                    'mail_port_placeholder' => 'Mail Port',
                    'mail_username_label' => 'Mail brugernavn',
                    'mail_username_placeholder' => 'Mail brugernavn',
                    'mail_password_label' => 'Mail adgangskode',
                    'mail_password_placeholder' => 'Mail adgangskode',
                    'mail_encryption_label' => 'Mailkryptering',
                    'mail_encryption_placeholder' => 'Mailkryptering',
                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher-app-id',
                    'pusher_app_id_palceholder' => 'Pusher-app-id',
                    'pusher_app_key_label' => 'Pusher-appnøgle',
                    'pusher_app_key_palceholder' => 'Pusher-appnøgle',
                    'pusher_app_secret_label' => 'Pusher-apphemmelighed',
                    'pusher_app_secret_palceholder' => 'Pusher-apphemmelighed',
                ],
                'buttons' => [
                    'setup_database' => 'Opsætningsdatabase',
                    'setup_application' => 'Opsætning af applikation',
                    'install' => 'Installere',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Trin 3 | Miljøindstillinger | Klassisk redaktør',
            'title' => 'Klassisk miljøredaktør',
            'save' => 'Gem .env',
            'back' => 'Brug formularguiden',
            'install' => 'Gem og installer',
        ],
        'success' => 'Dine .env-filindstillinger er blevet gemt.',
        'errors' => 'Kan ikke gemme .env-filen. Opret den manuelt.',
    ],
    'install' => 'Installere',
    'installed' => [
        'success_log_message' => 'CarePro-installationsprogrammet INSTALLERET med succes',
    ],
    'final' => [
        'title' => 'Installation afsluttet',
        'templateTitle' => 'Installation afsluttet',
        'finished' => 'Applikationen er installeret.',
        'migration' => '',
        'console' => 'Applikationskonsolens output:',
        'log' => 'Indtastning af installationslog:',
        'env' => 'Endelig .env-fil:',
        'exit' => 'Klik her for at afslutte',
    ],
    'updater' => [
        'title' => 'CarePro Updater',
        'welcome' => [
            'title' => 'Velkommen til Updater',
            'message' => 'Velkommen til opdateringsguiden.',
        ],
        'overview' => [
            'title' => 'Oversigt',
            'message' => 'Der er 1 opdatering. | Der er: nummeropdateringer.',
            'install_updates' => 'Installer opdateringer',
        ],
        'final' => [
            'title' => 'Færdig',
            'finished' => 'Applikationens database er blevet opdateret.',
            'exit' => 'Klik her for at afslutte',
        ],
        'log' => [
            'success_message' => 'CarePro-installationsprogrammet OPDATERET med succes',
        ],
    ],
];