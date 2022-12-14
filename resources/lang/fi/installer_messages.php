<?php 

return [
    'title' => 'CarePro Installer',
    'next' => 'Seuraava askel',
    'back' => 'Edellinen',
    'finish' => 'Asentaa',
    'forms' => [
        'errorTitle' => 'Seuraavat virheet tapahtuivat:',
    ],
    'welcome' => [
        'templateTitle' => 'Tervetuloa',
        'title' => 'CarePro Installer',
        'message' => 'Ohjattu helppo asennus ja asennus.',
        'next' => 'Tarkista vaatimukset',
    ],
    'requirements' => [
        'templateTitle' => 'Vaihe 1 | Palvelinvaatimukset',
        'title' => 'Palvelinvaatimukset',
        'next' => 'Tarkista luvat',
    ],
    'permissions' => [
        'templateTitle' => 'Vaihe 2 | käyttöoikeudet',
        'title' => 'käyttöoikeudet',
        'next' => 'Määritä ympäristö',
    ],
    'environment' => [
        'menu' => [
            'templateTitle' => 'Vaihe 3 | Ympäristöasetukset',
            'title' => 'Ympäristöasetukset',
            'desc' => 'Valitse, miten haluat määrittää sovellusten <code> .env </code> -tiedoston.',
            'wizard-button' => 'Ohjatun lomakkeen määrittäminen',
            'classic-button' => 'Klassinen tekstieditori',
        ],
        'wizard' => [
            'templateTitle' => 'Vaihe 3 | Ympäristöasetukset | Ohjattu velho',
            'title' => 'Ohjattu <koodi> .env </code> -toiminto',
            'tabs' => [
                'environment' => 'ympäristö',
                'database' => 'Tietokanta',
                'application' => 'hakemus',
            ],
            'form' => [
                'name_required' => 'Ympäristön nimi vaaditaan.',
                'app_name_label' => 'Sovelluksen nimi',
                'app_name_placeholder' => 'Sovelluksen nimi',
                'app_environment_label' => 'Sovellusympäristö',
                'app_environment_label_local' => 'paikallinen',
                'app_environment_label_developement' => 'kehitys',
                'app_environment_label_qa' => 'qa',
                'app_environment_label_production' => 'tuotanto',
                'app_environment_label_other' => 'muut',
                'app_environment_placeholder_other' => 'Anna ympäristösi ...',
                'app_debug_label' => 'Sovelluksen virheenkorjaus',
                'app_debug_label_true' => 'Totta',
                'app_debug_label_false' => 'Väärä',
                'app_log_level_label' => 'Sovelluslokin taso',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'tiedot',
                'app_log_level_label_notice' => 'ilmoitus',
                'app_log_level_label_warning' => 'Varoitus',
                'app_log_level_label_error' => 'virhe',
                'app_log_level_label_critical' => 'kriittinen',
                'app_log_level_label_alert' => 'hälytys',
                'app_log_level_label_emergency' => 'hätä',
                'app_url_label' => 'Sovelluksen URL-osoite',
                'app_url_placeholder' => 'Sovelluksen URL-osoite',
                'db_connection_failed' => 'Tietokantaan ei saatu yhteyttä.',
                'db_connection_label' => 'Tietokantayhteys',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Tietokannan isäntä',
                'db_host_placeholder' => 'Tietokannan isäntä',
                'db_port_label' => 'Tietokannan portti',
                'db_port_placeholder' => 'Tietokannan portti',
                'db_name_label' => 'Tietokannan nimi',
                'db_name_placeholder' => 'Tietokannan nimi',
                'db_username_label' => 'Tietokannan käyttäjänimi',
                'db_username_placeholder' => 'Tietokannan käyttäjänimi',
                'db_password_label' => 'Tietokannan salasana',
                'db_password_placeholder' => 'Tietokannan salasana',
                'app_tabs' => [
                    'more_info' => 'Lisätietoja',
                    'broadcasting_title' => '',
                    'broadcasting_label' => 'Lähetysohjain',
                    'broadcasting_placeholder' => 'Lähetysohjain',
                    'cache_label' => 'Välimuistin ohjain',
                    'cache_placeholder' => 'Välimuistin ohjain',
                    'session_label' => 'Istunnon ohjain',
                    'session_placeholder' => 'Istunnon ohjain',
                    'queue_label' => 'Jonon ohjain',
                    'queue_placeholder' => 'Jonon ohjain',
                    'redis_label' => 'Redis Driver',
                    'redis_host' => 'Redis-isäntä',
                    'redis_password' => 'Uudelleen salasana',
                    'redis_port' => 'Redis Port',
                    'mail_label' => 'posti',
                    'mail_driver_label' => 'Mail Driver',
                    'mail_driver_placeholder' => 'Mail Driver',
                    'mail_host_label' => 'Mail Host',
                    'mail_host_placeholder' => 'Mail Host',
                    'mail_port_label' => 'Postiportti',
                    'mail_port_placeholder' => 'Postiportti',
                    'mail_username_label' => 'Postin käyttäjänimi',
                    'mail_username_placeholder' => 'Postin käyttäjänimi',
                    'mail_password_label' => 'Postin salasana',
                    'mail_password_placeholder' => 'Postin salasana',
                    'mail_encryption_label' => 'Postin salaus',
                    'mail_encryption_placeholder' => 'Postin salaus',
                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher-sovellustunnus',
                    'pusher_app_id_palceholder' => 'Pusher-sovellustunnus',
                    'pusher_app_key_label' => 'Pusher-sovellusnäppäin',
                    'pusher_app_key_palceholder' => 'Pusher-sovellusnäppäin',
                    'pusher_app_secret_label' => 'Pusher-sovelluksen salaisuus',
                    'pusher_app_secret_palceholder' => 'Pusher-sovelluksen salaisuus',
                ],
                'buttons' => [
                    'setup_database' => 'Asennustietokanta',
                    'setup_application' => 'Asennussovellus',
                    'install' => 'Asentaa',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Vaihe 3 | Ympäristöasetukset | Klassinen toimittaja',
            'title' => 'Klassinen ympäristöeditori',
            'save' => 'Säästä .env',
            'back' => 'Käytä ohjattua muotoa',
            'install' => 'Tallenna ja asenna',
        ],
        'success' => '.Env-tiedostosi asetukset on tallennettu.',
        'errors' => '.Env-tiedostoa ei voi tallentaa. Luo se manuaalisesti.',
    ],
    'install' => 'Asentaa',
    'installed' => [
        'success_log_message' => 'CarePro Installer ASENNUS onnistuneesti',
    ],
    'final' => [
        'title' => 'Asennus valmis',
        'templateTitle' => 'Asennus valmis',
        'finished' => 'Sovellus on asennettu onnistuneesti.',
        'migration' => '',
        'console' => 'Sovelluskonsolilähtö:',
        'log' => 'Asennuslokin merkintä:',
        'env' => 'Lopullinen .env-tiedosto:',
        'exit' => 'Napsauta tätä poistuaksesi',
    ],
    'updater' => [
        'title' => 'CarePro-päivittäjä',
        'welcome' => [
            'title' => 'Tervetuloa päivittäjään',
            'message' => 'Tervetuloa ohjattuun päivitykseen.',
        ],
        'overview' => [
            'title' => 'Yleiskatsaus',
            'message' => 'Päivitystä on 1. | On: numeropäivityksiä.',
            'install_updates' => 'Asenna päivitykset',
        ],
        'final' => [
            'title' => 'päättynyt',
            'finished' => 'Sovelluksen tietokanta on päivitetty onnistuneesti.',
            'exit' => 'Napsauta tätä poistuaksesi',
        ],
        'log' => [
            'success_message' => 'CarePro Installer -päivityksen onnistuminen onnistui',
        ],
    ],
];