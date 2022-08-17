<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatabaseSeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //\Illuminate\Support\Facades\Artisan::call('db:seed',[]);
       /* $seeder = new DatabaseSeeder();
        $seeder->run();*/
        $this->roleTableSeeder();
        $this->adminSeeder();
        $this->settingTableSeeder();
        $this->permissionGroupSeeder();
        $this->permissionSeeder();
        $this->notesPermissionSeeder();
        $this->adminRoleSeeder();
        $this->adminRolePermissionSeeder();
        $this->adminUserRoleSeeder();
        $this->employmentPermissionSeeder();
        $this->addCurrencySetting();
        $this->currencyNameSeeder();
        $this->categoriesPermissionSeeder();
        $this->orderSettingSeeder();
        $this->orderCommentPermissionSeeder();
        $this->paymentMethodsSeeder();
        $this->socialLoginSettingsSeeder();
        $this->invoiceCategorySeeder();
        $this->invoiceCategoryPermissionSeeder();
        $this->orderSettingCategorySeeder();
        $this->jobCategoryPermissionSeeder();
        $this->emailsPermissionSeeder();
        $this->smsGatewaysSeeder();
        $this->templatePermissionSeeder();
        $this->interviewPermissionSeeder();
        $this->testPermissionsSeeder();
        $this->testResultPermissionSeeder();
        $this->blogPermissionSeeder();
        $this->templateSeeder();
        $this->countriesTableSeeder();

    }
    public function countriesTableSeeder()
    {
        \App\Country::insert([
            [
                'name'=>'Aaland Islands',
                'iso_code_2'=>'AX',
                'iso_code_3'=>'ALA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euro',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Afghanistan',
                'iso_code_2'=>'AF',
                'iso_code_3'=>'AFG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Afghani',
                'currency_code'=>'AFN',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Albania',
                'iso_code_2'=>'AL',
                'iso_code_3'=>'ALB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lek',
                'currency_code'=>'ALL',
                'symbol_left'=>'L',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Algeria',
                'iso_code_2'=>'DZ',
                'iso_code_3'=>'DZA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Algerian Dinar',
                'currency_code'=>'DZD',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'American Samoa',
                'iso_code_2'=>'AS',
                'iso_code_3'=>'ASM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Andorra',
                'iso_code_2'=>'AD',
                'iso_code_3'=>'AND',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Angola',
                'iso_code_2'=>'AO',
                'iso_code_3'=>'AGO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Angolan kwanza',
                'currency_code'=>'AOA',
                'symbol_left'=>'Kz',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Anguilla',
                'iso_code_2'=>'AI',
                'iso_code_3'=>'AIA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Antarctica',
                'iso_code_2'=>'AQ',
                'iso_code_3'=>'ATA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Antarctican dollar',
                'currency_code'=>'AQD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Antigua and Barbuda',
                'iso_code_2'=>'AG',
                'iso_code_3'=>'ATG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Argentina',
                'iso_code_2'=>'AR',
                'iso_code_3'=>'ARG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Peso',
                'currency_code'=>'ARS',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Armenia',
                'iso_code_2'=>'AM',
                'iso_code_3'=>'ARM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dram',
                'currency_code'=>'AMD',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Aruba',
                'iso_code_2'=>'AW',
                'iso_code_3'=>'ABW',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Netherlands Antilles Guilder',
                'currency_code'=>'ANG',
                'symbol_left'=>'ƒ',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Australia',
                'iso_code_2'=>'AU',
                'iso_code_3'=>'AUS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Austria',
                'iso_code_2'=>'AT',
                'iso_code_3'=>'AUT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Azerbaijan',
                'iso_code_2'=>'AZ',
                'iso_code_3'=>'AZE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Manat',
                'currency_code'=>'AZN',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bahamas',
                'iso_code_2'=>'BS',
                'iso_code_3'=>'BHS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Bahamian Dollar',
                'currency_code'=>'BSD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bahrain',
                'iso_code_2'=>'BH',
                'iso_code_3'=>'BHR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Bahraini Dinar',
                'currency_code'=>'BHD',
                'symbol_left'=>'.?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bangladesh',
                'iso_code_2'=>'BD',
                'iso_code_3'=>'BGD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Taka',
                'currency_code'=>'BDT',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Barbados',
                'iso_code_2'=>'BB',
                'iso_code_3'=>'BRB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Barbadian Dollar',
                'currency_code'=>'BBD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Belarus',
                'iso_code_2'=>'BY',
                'iso_code_3'=>'BLR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Belarus Ruble',
                'currency_code'=>'BYR',
                'symbol_left'=>'Br',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Belgium',
                'iso_code_2'=>'BE',
                'iso_code_3'=>'BEL',
                'address_format'=>'{firstname} {lastname} {company} {address_1} {address_2} {postcode} {city} {country}',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Belize',
                'iso_code_2'=>'BZ',
                'iso_code_3'=>'BLZ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Belizean Dollar',
                'currency_code'=>'BZD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Benin',
                'iso_code_2'=>'BJ',
                'iso_code_3'=>'BEN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bermuda',
                'iso_code_2'=>'BM',
                'iso_code_3'=>'BMU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Bermudian Dollar',
                'currency_code'=>'BMD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bhutan',
                'iso_code_2'=>'BT',
                'iso_code_3'=>'BTN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Indian Rupee',
                'currency_code'=>'INR',
                'symbol_left'=>'₹',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bolivia',
                'iso_code_2'=>'BO',
                'iso_code_3'=>'BOL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Boliviano',
                'currency_code'=>'BOB',
                'symbol_left'=>'Bs.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bonaire, Sint Eustatius and Saba',
                'iso_code_2'=>'BQ',
                'iso_code_3'=>'BES',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bosnia and Herzegovina',
                'iso_code_2'=>'BA',
                'iso_code_3'=>'BIH',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Bosnia and Herzegovina convertible mark',
                'currency_code'=>'BAM',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Botswana',
                'iso_code_2'=>'BW',
                'iso_code_3'=>'BWA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Pula',
                'currency_code'=>'BWP',
                'symbol_left'=>'P',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bouvet Island',
                'iso_code_2'=>'BV',
                'iso_code_3'=>'BVT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Norwegian Krone',
                'currency_code'=>'NOK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Brazil',
                'iso_code_2'=>'BR',
                'iso_code_3'=>'BRA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Brazil',
                'currency_code'=>'BRL',
                'symbol_left'=>'R$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'British Indian Ocean Territory',
                'iso_code_2'=>'IO',
                'iso_code_3'=>'IOT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Brunei Darussalam',
                'iso_code_2'=>'BN',
                'iso_code_3'=>'BRN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Bruneian Dollar',
                'currency_code'=>'BND',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Bulgaria',
                'iso_code_2'=>'BG',
                'iso_code_3'=>'BGR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lev',
                'currency_code'=>'BGN',
                'symbol_left'=>'??',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Burkina Faso',
                'iso_code_2'=>'BF',
                'iso_code_3'=>'BFA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Burundi',
                'iso_code_2'=>'BI',
                'iso_code_3'=>'BDI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Burundi Franc',
                'currency_code'=>'BIF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cambodia',
                'iso_code_2'=>'KH',
                'iso_code_3'=>'KHM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Riel',
                'currency_code'=>'KHR',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cameroon',
                'iso_code_2'=>'CM',
                'iso_code_3'=>'CMR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BEAC',
                'currency_code'=>'XAF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Canada',
                'iso_code_2'=>'CA',
                'iso_code_3'=>'CAN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Canadian Dollar',
                'currency_code'=>'CAD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Canary Islands',
                'iso_code_2'=>'IC',
                'iso_code_3'=>'ICA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euro',
                'currency_code'=>'EUR',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cape Verde',
                'iso_code_2'=>'CV',
                'iso_code_3'=>'CPV',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Escudo',
                'currency_code'=>'CVE',
                'symbol_left'=>'Esc',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cayman Islands',
                'iso_code_2'=>'KY',
                'iso_code_3'=>'CYM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Caymanian Dollar',
                'currency_code'=>'KYD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Central African Republic',
                'iso_code_2'=>'CF',
                'iso_code_3'=>'CAF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BEAC',
                'currency_code'=>'XAF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Chad',
                'iso_code_2'=>'TD',
                'iso_code_3'=>'TCD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BEAC',
                'currency_code'=>'XAF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Chile',
                'iso_code_2'=>'CL',
                'iso_code_3'=>'CHL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Chilean Peso',
                'currency_code'=>'CLP',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'China',
                'iso_code_2'=>'CN',
                'iso_code_3'=>'CHN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Yuan Renminbi',
                'currency_code'=>'CNY',
                'symbol_left'=>'¥',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Christmas Island',
                'iso_code_2'=>'CX',
                'iso_code_3'=>'CXR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cocos (Keeling) Islands',
                'iso_code_2'=>'CC',
                'iso_code_3'=>'CCK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Colombia',
                'iso_code_2'=>'CO',
                'iso_code_3'=>'COL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Peso',
                'currency_code'=>'COP',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Comoros',
                'iso_code_2'=>'KM',
                'iso_code_3'=>'COM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Comoran Franc',
                'currency_code'=>'KMF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Congo',
                'iso_code_2'=>'CG',
                'iso_code_3'=>'COG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BEAC',
                'currency_code'=>'XAF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cook Islands',
                'iso_code_2'=>'CK',
                'iso_code_3'=>'COK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'New Zealand Dollars',
                'currency_code'=>'NZD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Costa Rica',
                'iso_code_2'=>'CR',
                'iso_code_3'=>'CRI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Costa Rican Colon',
                'currency_code'=>'CRC',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cote D\'Ivoire',
                'iso_code_2'=>'CI',
                'iso_code_3'=>'CIV',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Croatia',
                'iso_code_2'=>'HR',
                'iso_code_3'=>'HRV',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Croatian Dinar',
                'currency_code'=>'HRK',
                'symbol_left'=>'kn',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cuba',
                'iso_code_2'=>'CU',
                'iso_code_3'=>'CUB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Cuban Peso',
                'currency_code'=>'CUP',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Curacao',
                'iso_code_2'=>'CW',
                'iso_code_3'=>'CUW',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Netherlands Antillean guilder',
                'currency_code'=>'NAF',
                'symbol_left'=>'ƒ',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Cyprus',
                'iso_code_2'=>'CY',
                'iso_code_3'=>'CYP',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Cypriot Pound',
                'currency_code'=>'CYP',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Czech Republic',
                'iso_code_2'=>'CZ',
                'iso_code_3'=>'CZE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Koruna',
                'currency_code'=>'CZK',
                'symbol_left'=>'K?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Democratic Republic of Congo',
                'iso_code_2'=>'CD',
                'iso_code_3'=>'COD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Congolese Frank',
                'currency_code'=>'CDF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Denmark',
                'iso_code_2'=>'DK',
                'iso_code_3'=>'DNK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Danish Krone',
                'currency_code'=>'DKK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Djibouti',
                'iso_code_2'=>'DJ',
                'iso_code_3'=>'DJI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Djiboutian Franc',
                'currency_code'=>'DJF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Dominica',
                'iso_code_2'=>'DM',
                'iso_code_3'=>'DMA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Dominican Republic',
                'iso_code_2'=>'DO',
                'iso_code_3'=>'DOM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dominican Peso',
                'currency_code'=>'DOP',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'East Timor',
                'iso_code_2'=>'TL',
                'iso_code_3'=>'TLS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Ecuador',
                'iso_code_2'=>'EC',
                'iso_code_3'=>'ECU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Sucre',
                'currency_code'=>'ECS',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Egypt',
                'iso_code_2'=>'EG',
                'iso_code_3'=>'EGY',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Egyptian Pound',
                'currency_code'=>'EGP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'El Salvador',
                'iso_code_2'=>'SV',
                'iso_code_3'=>'SLV',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Salvadoran Colon',
                'currency_code'=>'SVC',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Equatorial Guinea',
                'iso_code_2'=>'GQ',
                'iso_code_3'=>'GNQ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BEAC',
                'currency_code'=>'XAF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Eritrea',
                'iso_code_2'=>'ER',
                'iso_code_3'=>'ERI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Ethiopian Birr',
                'currency_code'=>'ETB',
                'symbol_left'=>'Nfk',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Estonia',
                'iso_code_2'=>'EE',
                'iso_code_3'=>'EST',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Estonian Kroon',
                'currency_code'=>'EEK',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Ethiopia',
                'iso_code_2'=>'ET',
                'iso_code_3'=>'ETH',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Ethiopian Birr',
                'currency_code'=>'ETB',
                'symbol_left'=>'Br',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Falkland Islands (Malvinas)',
                'iso_code_2'=>'FK',
                'iso_code_3'=>'FLK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Falkland Pound',
                'currency_code'=>'FKP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Faroe Islands',
                'iso_code_2'=>'FO',
                'iso_code_3'=>'FRO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Danish Krone',
                'currency_code'=>'DKK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Fiji',
                'iso_code_2'=>'FJ',
                'iso_code_3'=>'FJI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Fijian Dollar',
                'currency_code'=>'FJD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Finland',
                'iso_code_2'=>'FI',
                'iso_code_3'=>'FIN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'France, Metropolitan',
                'iso_code_2'=>'FR',
                'iso_code_3'=>'FRA',
                'address_format'=>'{firstname} {lastname} {company} {address_1} {address_2} {postcode} {city} {country}',
                'postcode_required'=>'1',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'French Guiana',
                'iso_code_2'=>'GF',
                'iso_code_3'=>'GUF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'French Polynesia',
                'iso_code_2'=>'PF',
                'iso_code_3'=>'PYF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFP Franc',
                'currency_code'=>'XPF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'French Southern Territories',
                'iso_code_2'=>'TF',
                'iso_code_3'=>'ATF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'FYROM',
                'iso_code_2'=>'MK',
                'iso_code_3'=>'MKD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Denar',
                'currency_code'=>'MKD',
                'symbol_left'=>'???',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Gabon',
                'iso_code_2'=>'GA',
                'iso_code_3'=>'GAB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BEAC',
                'currency_code'=>'XAF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Gambia',
                'iso_code_2'=>'GM',
                'iso_code_3'=>'GMB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dalasi',
                'currency_code'=>'GMD',
                'symbol_left'=>'D',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Georgia',
                'iso_code_2'=>'GE',
                'iso_code_3'=>'GEO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lari',
                'currency_code'=>'GEL',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Germany',
                'iso_code_2'=>'DE',
                'iso_code_3'=>'DEU',
                'address_format'=>'{company} {firstname} {lastname} {address_1} {address_2} {postcode} {city} {country}',
                'postcode_required'=>'1',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Ghana',
                'iso_code_2'=>'GH',
                'iso_code_3'=>'GHA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Ghana cedi',
                'currency_code'=>'GHS',
                'symbol_left'=>'GH¢',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Gibraltar',
                'iso_code_2'=>'GI',
                'iso_code_3'=>'GIB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Gibraltar Pound',
                'currency_code'=>'GIP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Greece',
                'iso_code_2'=>'GR',
                'iso_code_3'=>'GRC',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Greenland',
                'iso_code_2'=>'GL',
                'iso_code_3'=>'GRL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Danish Krone',
                'currency_code'=>'DKK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Grenada',
                'iso_code_2'=>'GD',
                'iso_code_3'=>'GRD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guadeloupe',
                'iso_code_2'=>'GP',
                'iso_code_3'=>'GLP',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guam',
                'iso_code_2'=>'GU',
                'iso_code_3'=>'GUM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guatemala',
                'iso_code_2'=>'GT',
                'iso_code_3'=>'GTM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Quetzal',
                'currency_code'=>'GTQ',
                'symbol_left'=>'Q',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guernsey',
                'iso_code_2'=>'GG',
                'iso_code_3'=>'GGY',
                'address_format'=>'',
                'postcode_required'=>'1',
                'status'=>'1',
                'currency_name'=>'Guernsey pound',
                'currency_code'=>'GGP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guinea',
                'iso_code_2'=>'GN',
                'iso_code_3'=>'GIN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Guinean Franc',
                'currency_code'=>'GNF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guinea-Bissau',
                'iso_code_2'=>'GW',
                'iso_code_3'=>'GNB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Guyana',
                'iso_code_2'=>'GY',
                'iso_code_3'=>'GUY',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Guyanaese Dollar',
                'currency_code'=>'GYD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Haiti',
                'iso_code_2'=>'HT',
                'iso_code_3'=>'HTI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Gourde',
                'currency_code'=>'HTG',
                'symbol_left'=>'G',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Heard and Mc Donald Islands',
                'iso_code_2'=>'HM',
                'iso_code_3'=>'HMD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Honduras',
                'iso_code_2'=>'HN',
                'iso_code_3'=>'HND',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lempira',
                'currency_code'=>'HNL',
                'symbol_left'=>'L',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Hong Kong',
                'iso_code_2'=>'HK',
                'iso_code_3'=>'HKG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'HKD',
                'currency_code'=>'HKD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Hungary',
                'iso_code_2'=>'HU',
                'iso_code_3'=>'HUN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Forint',
                'currency_code'=>'HUF',
                'symbol_left'=>'Ft',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Iceland',
                'iso_code_2'=>'IS',
                'iso_code_3'=>'ISL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Icelandic Krona',
                'currency_code'=>'ISK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'India',
                'iso_code_2'=>'IN',
                'iso_code_3'=>'IND',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Indian Rupee',
                'currency_code'=>'INR',
                'symbol_left'=>'₹',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Indonesia',
                'iso_code_2'=>'ID',
                'iso_code_3'=>'IDN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Indonesian Rupiah',
                'currency_code'=>'IDR',
                'symbol_left'=>'Rp',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Iran (Islamic Republic of)',
                'iso_code_2'=>'IR',
                'iso_code_3'=>'IRN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Iranian Rial',
                'currency_code'=>'IRR',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Iraq',
                'iso_code_2'=>'IQ',
                'iso_code_3'=>'IRQ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Iraqi Dinar',
                'currency_code'=>'IQD',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Ireland',
                'iso_code_2'=>'IE',
                'iso_code_3'=>'IRL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Israel',
                'iso_code_2'=>'IL',
                'iso_code_3'=>'ISR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Shekel',
                'currency_code'=>'ILS',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Italy',
                'iso_code_2'=>'IT',
                'iso_code_3'=>'ITA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Jamaica',
                'iso_code_2'=>'JM',
                'iso_code_3'=>'JAM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Jamaican Dollar',
                'currency_code'=>'JMD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Japan',
                'iso_code_2'=>'JP',
                'iso_code_3'=>'JPN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Japanese Yen',
                'currency_code'=>'JPY',
                'symbol_left'=>'¥',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Jersey',
                'iso_code_2'=>'JE',
                'iso_code_3'=>'JEY',
                'address_format'=>'',
                'postcode_required'=>'1',
                'status'=>'1',
                'currency_name'=>'Pound Sterling',
                'currency_code'=>'GBP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Jordan',
                'iso_code_2'=>'JO',
                'iso_code_3'=>'JOR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Jordanian Dinar',
                'currency_code'=>'JOD',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Kazakhstan',
                'iso_code_2'=>'KZ',
                'iso_code_3'=>'KAZ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Tenge',
                'currency_code'=>'KZT',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Kenya',
                'iso_code_2'=>'KE',
                'iso_code_3'=>'KEN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Kenyan Shilling',
                'currency_code'=>'KES',
                'symbol_left'=>'Sh',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Kiribati',
                'iso_code_2'=>'KI',
                'iso_code_3'=>'KIR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Korea, Republic of',
                'iso_code_2'=>'KR',
                'iso_code_3'=>'KOR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Won',
                'currency_code'=>'KRW',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Kuwait',
                'iso_code_2'=>'KW',
                'iso_code_3'=>'KWT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Kuwaiti Dinar',
                'currency_code'=>'KWD',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Kyrgyzstan',
                'iso_code_2'=>'KG',
                'iso_code_3'=>'KGZ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Som',
                'currency_code'=>'KGS',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Lao People\'s Democratic Republic',
                'iso_code_2'=>'LA',
                'iso_code_3'=>'LAO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Kip',
                'currency_code'=>'LAK',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Latvia',
                'iso_code_2'=>'LV',
                'iso_code_3'=>'LVA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lat',
                'currency_code'=>'LVL',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Lebanon',
                'iso_code_2'=>'LB',
                'iso_code_3'=>'LBN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lebanese Pound',
                'currency_code'=>'LBP',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Lesotho',
                'iso_code_2'=>'LS',
                'iso_code_3'=>'LSO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Loti',
                'currency_code'=>'LSL',
                'symbol_left'=>'L',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Liberia',
                'iso_code_2'=>'LR',
                'iso_code_3'=>'LBR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Liberian Dollar',
                'currency_code'=>'LRD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Libyan Arab Jamahiriya',
                'iso_code_2'=>'LY',
                'iso_code_3'=>'LBY',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Libyan Dinar',
                'currency_code'=>'LYD',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Liechtenstein',
                'iso_code_2'=>'LI',
                'iso_code_3'=>'LIE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Swiss Franc',
                'currency_code'=>'CHF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Lithuania',
                'iso_code_2'=>'LT',
                'iso_code_3'=>'LTU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lita',
                'currency_code'=>'LTL',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Luxembourg',
                'iso_code_2'=>'LU',
                'iso_code_3'=>'LUX',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Macau',
                'iso_code_2'=>'MO',
                'iso_code_3'=>'MAC',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Pataca',
                'currency_code'=>'MOP',
                'symbol_left'=>'P',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Madagascar',
                'iso_code_2'=>'MG',
                'iso_code_3'=>'MDG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Malagasy Franc',
                'currency_code'=>'MGA',
                'symbol_left'=>'Ar',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Malawi',
                'iso_code_2'=>'MW',
                'iso_code_3'=>'MWI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Malawian Kwacha',
                'currency_code'=>'MWK',
                'symbol_left'=>'MK',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Malaysia',
                'iso_code_2'=>'MY',
                'iso_code_3'=>'MYS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Ringgit',
                'currency_code'=>'MYR',
                'symbol_left'=>'RM',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Maldives',
                'iso_code_2'=>'MV',
                'iso_code_3'=>'MDV',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rufiyaa',
                'currency_code'=>'MVR',
                'symbol_left'=>'.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mali',
                'iso_code_2'=>'ML',
                'iso_code_3'=>'MLI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Malta',
                'iso_code_2'=>'MT',
                'iso_code_3'=>'MLT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Maltese Lira',
                'currency_code'=>'MTL',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Marshall Islands',
                'iso_code_2'=>'MH',
                'iso_code_3'=>'MHL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Martinique',
                'iso_code_2'=>'MQ',
                'iso_code_3'=>'MTQ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mauritania',
                'iso_code_2'=>'MR',
                'iso_code_3'=>'MRT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Ouguiya',
                'currency_code'=>'MRO',
                'symbol_left'=>'UM',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mauritius',
                'iso_code_2'=>'MU',
                'iso_code_3'=>'MUS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Mauritian Rupee',
                'currency_code'=>'MUR',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mayotte',
                'iso_code_2'=>'YT',
                'iso_code_3'=>'MYT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mexico',
                'iso_code_2'=>'MX',
                'iso_code_3'=>'MEX',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Peso',
                'currency_code'=>'MXN',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Micronesia, Federated States of',
                'iso_code_2'=>'FM',
                'iso_code_3'=>'FSM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Moldova, Republic of',
                'iso_code_2'=>'MD',
                'iso_code_3'=>'MDA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Leu',
                'currency_code'=>'MDL',
                'symbol_left'=>'L',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Monaco',
                'iso_code_2'=>'MC',
                'iso_code_3'=>'MCO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mongolia',
                'iso_code_2'=>'MN',
                'iso_code_3'=>'MNG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Tugrik',
                'currency_code'=>'MNT',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Montenegro',
                'iso_code_2'=>'ME',
                'iso_code_3'=>'MNE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euro',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Montserrat',
                'iso_code_2'=>'MS',
                'iso_code_3'=>'MSR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Morocco',
                'iso_code_2'=>'MA',
                'iso_code_3'=>'MAR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dirham',
                'currency_code'=>'MAD',
                'symbol_left'=>'?.?.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Mozambique',
                'iso_code_2'=>'MZ',
                'iso_code_3'=>'MOZ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Metical',
                'currency_code'=>'MZN',
                'symbol_left'=>'MT',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Myanmar',
                'iso_code_2'=>'MM',
                'iso_code_3'=>'MMR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Kyat',
                'currency_code'=>'MMK',
                'symbol_left'=>'Ks',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Namibia',
                'iso_code_2'=>'NA',
                'iso_code_3'=>'NAM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dollar',
                'currency_code'=>'NAD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Nauru',
                'iso_code_2'=>'NR',
                'iso_code_3'=>'NRU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Nepal',
                'iso_code_2'=>'NP',
                'iso_code_3'=>'NPL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Nepalese Rupee',
                'currency_code'=>'NPR',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Netherlands',
                'iso_code_2'=>'NL',
                'iso_code_3'=>'NLD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Netherlands Antilles',
                'iso_code_2'=>'AN',
                'iso_code_3'=>'ANT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Netherlands Antilles Guilder',
                'currency_code'=>'ANG',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'New Caledonia',
                'iso_code_2'=>'NC',
                'iso_code_3'=>'NCL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFP Franc',
                'currency_code'=>'XPF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'New Zealand',
                'iso_code_2'=>'NZ',
                'iso_code_3'=>'NZL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'New Zealand Dollars',
                'currency_code'=>'NZD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Nicaragua',
                'iso_code_2'=>'NI',
                'iso_code_3'=>'NIC',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Cordoba Oro',
                'currency_code'=>'NIO',
                'symbol_left'=>'C$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Niger',
                'iso_code_2'=>'NE',
                'iso_code_3'=>'NER',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Nigeria',
                'iso_code_2'=>'NG',
                'iso_code_3'=>'NGA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Naira',
                'currency_code'=>'NGN',
                'symbol_left'=>'₦',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Niue',
                'iso_code_2'=>'NU',
                'iso_code_3'=>'NIU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'New Zealand Dollars',
                'currency_code'=>'NZD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Norfolk Island',
                'iso_code_2'=>'NF',
                'iso_code_3'=>'NFK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'North Korea',
                'iso_code_2'=>'KP',
                'iso_code_3'=>'PRK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Won',
                'currency_code'=>'KPW',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Northern Mariana Islands',
                'iso_code_2'=>'MP',
                'iso_code_3'=>'MNP',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Norway',
                'iso_code_2'=>'NO',
                'iso_code_3'=>'NOR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Norwegian Krone',
                'currency_code'=>'NOK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Oman',
                'iso_code_2'=>'OM',
                'iso_code_3'=>'OMN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Sul Rial',
                'currency_code'=>'OMR',
                'symbol_left'=>'?.?.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Pakistan',
                'iso_code_2'=>'PK',
                'iso_code_3'=>'PAK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rupee',
                'currency_code'=>'PKR',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Palau',
                'iso_code_2'=>'PW',
                'iso_code_3'=>'PLW',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Palestinian Territory, Occupied',
                'iso_code_2'=>'PS',
                'iso_code_3'=>'PSE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Jordanian dinar',
                'currency_code'=>'JOD',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Panama',
                'iso_code_2'=>'PA',
                'iso_code_3'=>'PAN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Balboa',
                'currency_code'=>'PAB',
                'symbol_left'=>'B/.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Papua New Guinea',
                'iso_code_2'=>'PG',
                'iso_code_3'=>'PNG',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Kina',
                'currency_code'=>'PGK',
                'symbol_left'=>'K',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Paraguay',
                'iso_code_2'=>'PY',
                'iso_code_3'=>'PRY',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Guarani',
                'currency_code'=>'PYG',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Peru',
                'iso_code_2'=>'PE',
                'iso_code_3'=>'PER',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Nuevo Sol',
                'currency_code'=>'PEN',
                'symbol_left'=>'S/.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Philippines',
                'iso_code_2'=>'PH',
                'iso_code_3'=>'PHL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Peso',
                'currency_code'=>'PHP',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Pitcairn',
                'iso_code_2'=>'PN',
                'iso_code_3'=>'PCN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'New Zealand Dollars',
                'currency_code'=>'NZD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Poland',
                'iso_code_2'=>'PL',
                'iso_code_3'=>'POL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Zloty',
                'currency_code'=>'PLN',
                'symbol_left'=>'z?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Portugal',
                'iso_code_2'=>'PT',
                'iso_code_3'=>'PRT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Puerto Rico',
                'iso_code_2'=>'PR',
                'iso_code_3'=>'PRI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Qatar',
                'iso_code_2'=>'QA',
                'iso_code_3'=>'QAT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rial',
                'currency_code'=>'QAR',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Reunion',
                'iso_code_2'=>'RE',
                'iso_code_3'=>'REU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Romania',
                'iso_code_2'=>'RO',
                'iso_code_3'=>'ROM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Leu',
                'currency_code'=>'RON',
                'symbol_left'=>'lei',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Russian Federation',
                'iso_code_2'=>'RU',
                'iso_code_3'=>'RUS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Ruble',
                'currency_code'=>'RUB',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Rwanda',
                'iso_code_2'=>'RW',
                'iso_code_3'=>'RWA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rwanda Franc',
                'currency_code'=>'RWF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Saint Kitts and Nevis',
                'iso_code_2'=>'KN',
                'iso_code_3'=>'KNA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Saint Lucia',
                'iso_code_2'=>'LC',
                'iso_code_3'=>'LCA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Saint Vincent and the Grenadines',
                'iso_code_2'=>'VC',
                'iso_code_3'=>'VCT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'East Caribbean Dollar',
                'currency_code'=>'XCD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Samoa',
                'iso_code_2'=>'WS',
                'iso_code_3'=>'WSM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'T',
                'symbol_right'=>'',
            ],
            [
                'name'=>'San Marino',
                'iso_code_2'=>'SM',
                'iso_code_3'=>'SMR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Sao Tome and Principe',
                'iso_code_2'=>'ST',
                'iso_code_3'=>'STP',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dobra',
                'currency_code'=>'STD',
                'symbol_left'=>'Db',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Saudi Arabia',
                'iso_code_2'=>'SA',
                'iso_code_3'=>'SAU',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Riyal',
                'currency_code'=>'SAR',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Senegal',
                'iso_code_2'=>'SN',
                'iso_code_3'=>'SEN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Serbia',
                'iso_code_2'=>'RS',
                'iso_code_3'=>'SRB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Serbian dinar',
                'currency_code'=>'RSD',
                'symbol_left'=>'???.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Seychelles',
                'iso_code_2'=>'SC',
                'iso_code_3'=>'SYC',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rupee',
                'currency_code'=>'SCR',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Sierra Leone',
                'iso_code_2'=>'SL',
                'iso_code_3'=>'SLE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Leone',
                'currency_code'=>'SLL',
                'symbol_left'=>'Le',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Singapore',
                'iso_code_2'=>'SG',
                'iso_code_3'=>'SGP',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dollar',
                'currency_code'=>'SGD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Slovak Republic',
                'iso_code_2'=>'SK',
                'iso_code_3'=>'SVK',
                'address_format'=>'{firstname} {lastname} {company} {address_1} {address_2} {city} {postcode} {zone} {country}',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Koruna',
                'currency_code'=>'SKK',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Slovenia',
                'iso_code_2'=>'SI',
                'iso_code_3'=>'SVN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Solomon Islands',
                'iso_code_2'=>'SB',
                'iso_code_3'=>'SLB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Solomon Islands Dollar',
                'currency_code'=>'SBD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Somalia',
                'iso_code_2'=>'SO',
                'iso_code_3'=>'SOM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Shilling',
                'currency_code'=>'SOS',
                'symbol_left'=>'Sh',
                'symbol_right'=>'',
            ],
            [
                'name'=>'South Africa',
                'iso_code_2'=>'ZA',
                'iso_code_3'=>'ZAF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rand',
                'currency_code'=>'ZAR',
                'symbol_left'=>'R',
                'symbol_right'=>'',
            ],
            [
                'name'=>'South Georgia & South Sandwich Islands',
                'iso_code_2'=>'GS',
                'iso_code_3'=>'SGS',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Pound Sterling',
                'currency_code'=>'GBP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'South Sudan',
                'iso_code_2'=>'SS',
                'iso_code_3'=>'SSD',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'South Sudanese Pound',
                'currency_code'=>'SSP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Spain',
                'iso_code_2'=>'ES',
                'iso_code_3'=>'ESP',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Sri Lanka',
                'iso_code_2'=>'LK',
                'iso_code_3'=>'LKA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rupee',
                'currency_code'=>'LKR',
                'symbol_left'=>'Rs',
                'symbol_right'=>'',
            ],
            [
                'name'=>'St. Barthelemy',
                'iso_code_2'=>'BL',
                'iso_code_3'=>'BLM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euro',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'St. Helena',
                'iso_code_2'=>'SH',
                'iso_code_3'=>'SHN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Pound Sterling',
                'currency_code'=>'GBP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'St. Martin (French part)',
                'iso_code_2'=>'MF',
                'iso_code_3'=>'MAF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Netherlands Antillean guilder',
                'currency_code'=>'ANG',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'St. Pierre and Miquelon',
                'iso_code_2'=>'PM',
                'iso_code_3'=>'SPM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euro',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Sudan',
                'iso_code_2'=>'SD',
                'iso_code_3'=>'SDN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dinar',
                'currency_code'=>'SDG',
                'symbol_left'=>'?.?.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Suriname',
                'iso_code_2'=>'SR',
                'iso_code_3'=>'SUR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Surinamese Guilder',
                'currency_code'=>'SRD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Svalbard and Jan Mayen Islands',
                'iso_code_2'=>'SJ',
                'iso_code_3'=>'SJM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Norwegian Krone',
                'currency_code'=>'NOK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Swaziland',
                'iso_code_2'=>'SZ',
                'iso_code_3'=>'SWZ',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lilangeni',
                'currency_code'=>'SZL',
                'symbol_left'=>'L',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Sweden',
                'iso_code_2'=>'SE',
                'iso_code_3'=>'SWE',
                'address_format'=>'{company} {firstname} {lastname} {address_1} {address_2} {postcode} {city} {country}',
                'postcode_required'=>'1',
                'status'=>'1',
                'currency_name'=>'Krona',
                'currency_code'=>'SEK',
                'symbol_left'=>'kr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Switzerland',
                'iso_code_2'=>'CH',
                'iso_code_3'=>'CHE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Swiss Franc',
                'currency_code'=>'CHF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Syrian Arab Republic',
                'iso_code_2'=>'SY',
                'iso_code_3'=>'SYR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Syrian Pound',
                'currency_code'=>'SYP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Taiwan',
                'iso_code_2'=>'TW',
                'iso_code_3'=>'TWN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dollar',
                'currency_code'=>'TWD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Tajikistan',
                'iso_code_2'=>'TJ',
                'iso_code_3'=>'TJK',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Tajikistan Ruble',
                'currency_code'=>'TJS',
                'symbol_left'=>'??',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Tanzania, United Republic of',
                'iso_code_2'=>'TZ',
                'iso_code_3'=>'TZA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Shilling',
                'currency_code'=>'TZS',
                'symbol_left'=>'Sh',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Thailand',
                'iso_code_2'=>'TH',
                'iso_code_3'=>'THA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Baht',
                'currency_code'=>'THB',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Togo',
                'iso_code_2'=>'TG',
                'iso_code_3'=>'TGO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFA Franc BCEAO',
                'currency_code'=>'XOF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Tokelau',
                'iso_code_2'=>'TK',
                'iso_code_3'=>'TKL',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'New Zealand Dollars',
                'currency_code'=>'NZD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Tonga',
                'iso_code_2'=>'TO',
                'iso_code_3'=>'TON',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'PaÕanga',
                'currency_code'=>'TOP',
                'symbol_left'=>'T$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Trinidad and Tobago',
                'iso_code_2'=>'TT',
                'iso_code_3'=>'TTO',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Trinidad and Tobago Dollar',
                'currency_code'=>'TTD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Tunisia',
                'iso_code_2'=>'TN',
                'iso_code_3'=>'TUN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Tunisian Dinar',
                'currency_code'=>'TND',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Turkey',
                'iso_code_2'=>'TR',
                'iso_code_3'=>'TUR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Lira',
                'currency_code'=>'TRY',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Turkmenistan',
                'iso_code_2'=>'TM',
                'iso_code_3'=>'TKM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Manat',
                'currency_code'=>'TMT',
                'symbol_left'=>'m',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Turks and Caicos Islands',
                'iso_code_2'=>'TC',
                'iso_code_3'=>'TCA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Tuvalu',
                'iso_code_2'=>'TV',
                'iso_code_3'=>'TUV',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Australian Dollars',
                'currency_code'=>'AUD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Uganda',
                'iso_code_2'=>'UG',
                'iso_code_3'=>'UGA',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Shilling',
                'currency_code'=>'UGX',
                'symbol_left'=>'Sh',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Ukraine',
                'iso_code_2'=>'UA',
                'iso_code_3'=>'UKR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Hryvnia',
                'currency_code'=>'UAH',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'United Arab Emirates',
                'iso_code_2'=>'AE',
                'iso_code_3'=>'ARE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dirham',
                'currency_code'=>'AED',
                'symbol_left'=>'?.?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'United Kingdom',
                'iso_code_2'=>'GB',
                'iso_code_3'=>'GBR',
                'address_format'=>'',
                'postcode_required'=>'1',
                'status'=>'1',
                'currency_name'=>'Pound Sterling',
                'currency_code'=>'GBP',
                'symbol_left'=>'£',
                'symbol_right'=>'',
            ],
            [
                'name'=>'United States',
                'iso_code_2'=>'US',
                'iso_code_3'=>'USA',
                'address_format'=>'{firstname} {lastname} {company} {address_1} {address_2} {city}, {zone} {postcode} {country}',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'United States Minor Outlying Islands',
                'iso_code_2'=>'UM',
                'iso_code_3'=>'UMI',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Uruguay',
                'iso_code_2'=>'UY',
                'iso_code_3'=>'URY',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Peso',
                'currency_code'=>'UYU',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Uzbekistan',
                'iso_code_2'=>'UZ',
                'iso_code_3'=>'UZB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Som',
                'currency_code'=>'UZS',
                'symbol_left'=>'',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Vanuatu',
                'iso_code_2'=>'VU',
                'iso_code_3'=>'VUT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Vatu',
                'currency_code'=>'VUV',
                'symbol_left'=>'Vt',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Vatican City State (Holy See)',
                'iso_code_2'=>'VA',
                'iso_code_3'=>'VAT',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Euros',
                'currency_code'=>'EUR',
                'symbol_left'=>'€',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Venezuela',
                'iso_code_2'=>'VE',
                'iso_code_3'=>'VEN',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Bolivar',
                'currency_code'=>'VEF',
                'symbol_left'=>'Bs F',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Viet Nam',
                'iso_code_2'=>'VN',
                'iso_code_3'=>'VNM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dong',
                'currency_code'=>'VND',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Virgin Islands (British)',
                'iso_code_2'=>'VG',
                'iso_code_3'=>'VGB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Virgin Islands (U.S.)',
                'iso_code_2'=>'VI',
                'iso_code_3'=>'VIR',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'United States Dollar',
                'currency_code'=>'USD',
                'symbol_left'=>'$',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Wallis and Futuna Islands',
                'iso_code_2'=>'WF',
                'iso_code_3'=>'WLF',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'CFP Franc',
                'currency_code'=>'XPF',
                'symbol_left'=>'Fr',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Western Sahara',
                'iso_code_2'=>'EH',
                'iso_code_3'=>'ESH',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Dirham',
                'currency_code'=>'MAD',
                'symbol_left'=>'?.?.',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Yemen',
                'iso_code_2'=>'YE',
                'iso_code_3'=>'YEM',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Rial',
                'currency_code'=>'YER',
                'symbol_left'=>'?',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Zambia',
                'iso_code_2'=>'ZM',
                'iso_code_3'=>'ZMB',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Kwacha',
                'currency_code'=>'ZMK',
                'symbol_left'=>'ZK',
                'symbol_right'=>'',
            ],
            [
                'name'=>'Zimbabwe',
                'iso_code_2'=>'ZW',
                'iso_code_3'=>'ZWE',
                'address_format'=>'',
                'postcode_required'=>'0',
                'status'=>'1',
                'currency_name'=>'Zimbabwe Dollar',
                'currency_code'=>'ZWD',
                'symbol_left'=>'P',
                'symbol_right'=>'',
            ],

        ]);
    }

    public function templateSeeder()
    {
        \App\Template::create([
            'name'=> 'Application',
            'enabled'=>1,
            'directory'=>'application'
        ]);
    }

    public function blogPermissionSeeder()
    {
        \App\PermissionGroup::insert([
            [
                'name'=>'blog',
                'sort_order'=>'13',
                'id'=>'13'
            ]
        ]);


        \App\Permission::insert([
            [
                'name'=>'view_blogs',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'view_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'create_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'edit_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'delete_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'manage_blog_categories',
                'permission_group_id'=>'13'
            ]

        ]);

    }

    public function testResultPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'delete_test_result',
                'permission_group_id'=>'12'
            ]
        ]);

    }

    public function testPermissionsSeeder()
    {
        \App\PermissionGroup::insert([
            [
                'name'=>'tests',
                'sort_order'=>'12',
                'id'=>'12'
            ]
        ]);

        \App\Permission::insert([
            [
                'name'=>'view_tests',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'create_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'edit_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'delete_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test_results',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test_questions',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test_question',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'create_test_question',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'edit_test_question',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'delete_test_question',
                'permission_group_id'=>'12'
            ],

        ]);

    }

    public function interviewPermissionSeeder()
    {
        \App\PermissionGroup::insert([
            [
                'name'=>'interviews',
                'sort_order'=>'11',
                'id'=>'11'
            ]
        ]);


        \App\Permission::insert([
            [
                'name'=>'view_interviews',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'view_interview',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'create_interview',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'edit_interview',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'delete_interview',
                'permission_group_id'=>'11'
            ],

        ]);




    }

    public function templatePermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_email_templates',
                'permission_group_id'=>'6'
            ] ,
            [
                'name'=>'view_email_template',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'create_email_template',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'edit_email_template',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'delete_email_template',
                'permission_group_id'=>'6'
            ],

            //sms templates
            [
                'name'=>'view_sms_templates',
                'permission_group_id'=>'7'
            ] ,
            [
                'name'=>'view_sms_template',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'create_sms_template',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'edit_sms_template',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'delete_sms_template',
                'permission_group_id'=>'7'
            ],

        ]);
    }

    public function smsGatewaysSeeder()
    {
        \App\SmsGateway::create(
            [
                'id'=>1,
                'gateway_name'=>'Clickatell',
                'url'=>'https://www.clickatell.com',
                'code'=>'clickatell',
                'active'=>0
            ]
        );

        \App\SmsGatewayField::create([
            'sms_gateway_id'=>'1',
            'key'=>'api_key',
            'type'=>'text',
        ]);

        $smsGateway = \App\SmsGateway::create([
            'gateway_name'=>'Bulk SMS',
            'url'=>'https://www.bulksms.com',
            'code'=>'bulksms'
        ]);

        $smsGateway->smsGatewayFields()->saveMany([
            new \App\SmsGatewayField([
                'key'=>'username',
                'type'=>'text',
            ]),
            new \App\SmsGatewayField([
                'key'=>'password',
                'type'=>'text',
            ])
        ]);

        $smsGateway = \App\SmsGateway::create([
            'gateway_name'=>'Smart SMS Solutions',
            'url'=>'http://smartsmssolutions.com',
            'code'=>'smartsms'
        ]);

        $smsGateway->smsGatewayFields()->saveMany([
            new \App\SmsGatewayField([
                'key'=>'username',
                'type'=>'text',
            ]),
            new \App\SmsGatewayField([
                'key'=>'password',
                'type'=>'text',
            ]),
            new \App\SmsGatewayField([
                'key'=>'sender_name',
                'type'=>'text'
            ])
        ]);

        $smsGateway = \App\SmsGateway::create([
            'gateway_name'=>'Cheap Global SMS',
            'url'=>'https://cheapglobalsms.com',
            'code'=>'cheapglobal'
        ]);

        $smsGateway->smsGatewayFields()->saveMany([
            new \App\SmsGatewayField([
                'key'=>'sub_account',
                'type'=>'text',
            ]),
            new \App\SmsGatewayField([
                'key'=>'sub_account_pass',
                'type'=>'text',
            ]),
            new \App\SmsGatewayField([
                'key'=>'sender_name',
                'type'=>'text'
            ])
        ]);




    }

    public function emailsPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_emails',
                'permission_group_id'=>'6'
            ] ,
            [
                'name'=>'view_email',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'create_email',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'edit_email',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'delete_email',
                'permission_group_id'=>'6'
            ],
            //email resources
            [
                'name'=>'view_email_resources',
                'permission_group_id'=>'6'
            ] ,
            [
                'name'=>'view_email_resource',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'create_email_resource',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'edit_email_resource',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'delete_email_resource',
                'permission_group_id'=>'6'
            ],
            //text messages
            [
                'name'=>'view_text_messages',
                'permission_group_id'=>'7'
            ] ,
            [
                'name'=>'view_text_message',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'create_text_message',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'edit_text_message',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'delete_text_message',
                'permission_group_id'=>'7'
            ]



        ]);
    }

    public function jobCategoryPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_vacancy_categories',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'view_vacancy_category',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'create_vacancy_category',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'edit_vacancy_category',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'delete_vacancy_category',
                'permission_group_id'=>'5'
            ],

        ]);
    }

    public function orderSettingCategorySeeder()
    {
        \App\Setting::insert(
            [
                ['key'=>'order_invoice_category','type'=>'include','options'=>'admin.settings.includes.invoice-category'],
            ]);
    }

    public function invoiceCategoryPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_invoice_categories',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'view_invoice_category',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'create_invoice_category',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'edit_invoice_category',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'delete_invoice_category',
                'permission_group_id'=>'4'
            ],

        ]);
    }


    public function invoiceCategorySeeder()
    {
        \App\InvoiceCategory::insert([
            ['name'=>'Mobilization Fees','sort_order'=>1],
            ['name'=>'Placement Fees','sort_order'=>2]
        ]);
    }

    public function socialLoginSettingsSeeder()
    {
        \App\Setting::insert(
            [

                ['key'=>'social_callback_urls','type'=>'include','options'=>'admin.settings.includes.social-callbacks','value'=>'0'],
            ]
        );

        \App\Setting::insert(
            [
                ['key'=>'social_enable_facebook','type'=>'radio','options'=>'0=No,1=Yes'],
            ]);
        \App\Setting::insert(
            [
                ['key'=>'social_facebook_app_secret','type'=>'text'],
                ['key'=>'social_facebook_app_id','type'=>'text'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'social_enable_google','type'=>'radio','options'=>'0=No,1=Yes'],
            ]);
        \App\Setting::insert(
            [
                ['key'=>'social_google_app_secret','type'=>'text'],
                ['key'=>'social_google_app_id','type'=>'text'],
            ]);
    }

    public function paymentMethodsSeeder()
    {
        \App\PaymentMethod::create([
            'id'=>1,
            'name'=>'Paypal',
            'code'=>'paypal',
            'sort_order'=>1,
            'method_label'=>'Paypal'
        ]);

        \App\PaymentMethod::create([
            'id'=>2,
            'name'=>'Stripe',
            'code'=>'stripe',
            'sort_order'=>1,
            'method_label'=>'Stripe'
        ]);

        \App\PaymentMethod::create([
            'id'=>3,
            'name'=>'2Checkout',
            'code'=>'twocheckout',
            'sort_order'=>1,
            'method_label'=>'2Checkout'
        ]);

        \App\PaymentMethod::create([
            'id'=>4,
            'name'=>'Bank Transfer',
            'status'=>1,
            'code'=>'bank',
            'translate'=>1,
            'method_label'=>'Bank Transfer',
            'sort_order'=>1
        ]);

        \App\PaymentMethod::create([
            'id'=>5,
            'name'=>'Paystack',
            'code'=>'paystack',
            'sort_order'=>1,
            'method_label'=>'paystack'
        ]);

        \App\PaymentMethod::create([
            'id'=>6,
            'name'=>'Rave',
            'code'=>'rave',
            'sort_order'=>1,
            'method_label'=>'Rave'
        ]);

        //now add fields

        //bank
        \App\PaymentMethodField::create([
            'key'=>'details',
            'payment_method_id'=>4,
            'type'=>'textarea'
        ]);

        //paypal

        \App\PaymentMethodField::create([
            'key'=>'client_id',
            'payment_method_id'=>1,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret',
            'payment_method_id'=>1,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'mode',
            'payment_method_id'=>1,
            'type'=>'select',
            'options'=>'live=Live,sandbox=Sandbox'
        ]);

        //stripe
        \App\PaymentMethodField::create([
            'key'=>'public_key',
            'payment_method_id'=>2,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_key',
            'payment_method_id'=>2,
            'type'=>'text'
        ]);

        //2checkout
        \App\PaymentMethodField::create([
            'key'=>'account_number',
            'payment_method_id'=>3,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_word',
            'payment_method_id'=>3,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'mode',
            'payment_method_id'=>3,
            'type'=>'select',
            'options'=>'live=Live,sandbox=Sandbox'
        ]);

        //paystack

        \App\PaymentMethodField::create([
            'key'=>'public_key',
            'payment_method_id'=>5,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_key',
            'payment_method_id'=>5,
            'type'=>'text'
        ]);

        //rave
        \App\PaymentMethodField::create([
            'key'=>'public_key',
            'payment_method_id'=>6,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_key',
            'payment_method_id'=>6,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'mode',
            'payment_method_id'=>6,
            'type'=>'select',
            'options'=>'live=Live,test=Test'
        ]);
    }

    public function orderCommentPermissionSeeder()
    {
        \App\Permission::insert([

            [
                'name'=>'view_order_comments',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'view_order_comment',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'create_order_comment',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'edit_order_comment',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'delete_order_comment',
                'permission_group_id'=>'1'
            ]
        ]);
    }

    public function orderSettingSeeder()
    {
        \App\Setting::insert(
            [
                ['key'=>'order_instructions','type'=>'textarea','class'=>'form-control rte'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_enable_shortlist','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
            ]);
        //This is or auto invoice generation
        \App\Setting::insert(
            [
                ['key'=>'order_enable_invoice','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_invoice_amount','type'=>'text','class'=>'form-control digit'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_invoice_title','type'=>'text'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_invoice_description','type'=>'textarea'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_require_address','type'=>'radio','options'=>'0=No,1=Yes','value'=>'0'],
            ]);

    }


    public function categoriesPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_categories',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_category',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'create_category',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'edit_category',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'delete_category',
                'permission_group_id'=>'3'
            ],

        ]);
    }
    public function currencyNameSeeder()
    {
        \App\Setting::insert(
            [
                ['key'=>'general_currency_name','type'=>'text','value'=>'dollars'],
                ['key'=>'general_currency_code','type'=>'text','value'=>'USD'],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    public function roleTableSeeder()
    {
        $role = new \App\Role();
        $role->id =1;
        $role->name = 'Admin';
        $role->save();

        $role = new \App\Role();
        $role->id =2;
        $role->name = 'Employer';
        $role->save();

        $role = new \App\Role();
        $role->id =3;
        $role->name = 'Candidate';
        $role->save();
    }
    public function adminSeeder()
    {
        $user = new \App\User();
        $user->id=1;
        $user->name= 'Admin';
        $user->email = 'admin@email.com';
        $user->password = bcrypt('password');
        $user->role_id = 1;
        $user->save();
    }
    public function settingTableSeeder()
    {
        \App\Setting::insert(
            [
                ['key'=>'general_site_name','type'=>'text','options'=>''],
                ['key'=>'general_homepage_title','type'=>'text','options'=>''],
                ['key'=>'general_homepage_meta_desc','type'=>'textarea','options'=>''],
                ['key'=>'general_admin_email','type'=>'text','options'=>''],
                ['key'=>'general_address','type'=>'textarea','options'=>''],
                ['key'=>'general_tel','type'=>'text','options'=>''],
                ['key'=>'general_contact_email','type'=>'text','options'=>''],

            ]
        );

        \App\Setting::insert(
            [
                ['key'=>'general_enable_employer_registration','type'=>'radio','options'=>'1=Yes,0=No','value'=>1],
                ['key'=>'general_enable_candidate_registration','type'=>'radio','options'=>'1=Yes,0=No','value'=>1],
                ['key'=>'general_employer_verification','type'=>'radio','options'=>'0=No,1=Yes','value'=>'0'],
                ['key'=>'general_candidate_verification','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
                ['key'=>'general_employer_captcha','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
                ['key'=>'general_candidate_captcha','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
            ]
        );

        \App\Setting::insert(
            [
                ['key'=>'general_header_scripts','type'=>'textarea','options'=>''],
                ['key'=>'general_footer_scripts','type'=>'textarea','options'=>''],
                ['key'=>'general_disqus_shortcode','type'=>'text','options'=>''],
                ['key'=>'image_logo','type'=>'image','options'=>''],
                ['key'=>'image_icon','type'=>'image','options'=>''],
                ['key'=>'mail_protocol','type'=>'select','options'=>'mail=Mail,smtp=SMTP'],
                ['key'=>'mail_smtp_host','type'=>'text','options'=>''],
                ['key'=>'mail_smtp_username','type'=>'text','options'=>''],
                ['key'=>'mail_smtp_password','type'=>'text','options'=>''],
                ['key'=>'mail_smtp_port','type'=>'text','options'=>''],
                ['key'=>'mail_smtp_timeout','type'=>'text','options'=>''],
            ]
        );
    }

    public function permissionGroupSeeder()
    {
        \App\PermissionGroup::insert([
            [
                'name'=>'orders',
                'sort_order'=>'1',
                'id'=>'1'
            ],
            [
                'name'=>'employers',
                'sort_order'=>'2',
                'id'=>'2'
            ],
            [
                'name'=>'candidates',
                'sort_order'=>'3',
                'id'=>'3'
            ],
            [
                'name'=>'invoices',
                'sort_order'=>'4',
                'id'=>'4'
            ],
            [
                'name'=>'vacancies',
                'sort_order'=>'5',
                'id'=>'5'
            ],
            [
                'name'=>'emails',
                'sort_order'=>'6',
                'id'=>'6'
            ],
            [
                'name'=>'text_messaging',
                'sort_order'=>'7',
                'id'=>'7'
            ],
            [
                'name'=>'articles',
                'sort_order'=>'8',
                'id'=>'8'
            ],/*
                [
                    'name'=>'forms',
                    'sort_order'=>'9',
                    'id'=>'9'
                ],*/
            [
                'name'=>'settings',
                'sort_order'=>'10',
                'id'=>'10'
            ]

        ]);
    }
    public function permissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'manage_settings',
                'permission_group_id'=>'10'
            ],
            /*       [
                       'name'=>'manage_forms',
                       'permission_group_id'=>'9'
                   ],*/
            //orders
            [
                'name'=>'view_orders',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'view_order',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'create_order',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'edit_order',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'delete_order',
                'permission_group_id'=>'1'
            ],
            //EMPLOYERS
            [
                'name'=>'view_employers',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employer',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employer',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employer',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employer',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employer_notes',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employer_note',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employer_note',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employer_note',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employer_note',
                'permission_group_id'=>'2'
            ],
            //Candiddate
            [
                'name'=>'view_candidates',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_candidate',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'create_candidate',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'edit_candidate',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'delete_candidate',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_candidate_notes',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_candidate_note',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'create_candidate_note',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'edit_candidate_note',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'delete_candidate_note',
                'permission_group_id'=>'3'
            ],
            //INVOICES
            [
                'name'=>'view_invoices',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'view_invoice',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'approve_invoice',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'edit_invoice',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'create_invoice',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'delete_invoice',
                'permission_group_id'=>'4'
            ],
            //Vacancies
            [
                'name'=>'view_vacancies',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'view_vacancy',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'create_vacancy',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'edit_vacancy',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'delete_vacancy',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'view_applications',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'view_application',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'shortlist_application',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'delete_application',
                'permission_group_id'=>'5'
            ],
            //Articles
            [
                'name'=>'view_articles',
                'permission_group_id'=>'8'
            ],
            [
                'name'=>'view_article',
                'permission_group_id'=>'8'
            ],
            [
                'name'=>'create_article',
                'permission_group_id'=>'8'
            ],
            [
                'name'=>'edit_article',
                'permission_group_id'=>'8'
            ],
            [
                'name'=>'delete_article',
                'permission_group_id'=>'8'
            ],



        ]);
    }

    public function notesPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_candidate_attachments',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'create_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'edit_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'delete_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_employer_attachments',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employer_attachment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employer_attachment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employer_attachment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employer_attachment',
                'permission_group_id'=>'2'
            ]
        ]);
    }

    public function adminRoleSeeder()
    {
        $adminRole = new \App\AdminRole();
        $adminRole->id = 1;
        $adminRole->name = 'Super Administrator';
        $adminRole->save();

        $adminRole = new \App\AdminRole();
        $adminRole->id = 2;
        $adminRole->name = 'Administrator';
        $adminRole->save();
    }

    public function adminRolePermissionSeeder()
    {
        //create seeder for super administrator
        $permissions = \App\Permission::get();

        $adminRole = \App\AdminRole::find(1);
        $permissionList = [];
        foreach($permissions as $permission){
            $permissionList[] = $permission->id;
        }

        $adminRole->permissions()->sync($permissionList);


        //create seeder for administrator
        $permissions = \App\Permission::where('id','>',2)->get();

        $adminRole = \App\AdminRole::find(2);
        $permissionList = [];
        foreach($permissions as $permission){
            $permissionList[] = $permission->id;
        }

        $adminRole->permissions()->sync($permissionList);
    }

    public function adminUserRoleSeeder()
    {
        $user  = \App\User::find(1);
        $user->adminRoles()->sync([1]);
    }

    public function employmentPermissionSeeder()
    {
        \App\Permission::insert([
            [
                'name'=>'view_employments',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employment_comments',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employment_comment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employment_comment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employment_comment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employment_comment',
                'permission_group_id'=>'2'
            ]
        ]);
    }

    public function addCurrencySetting()
    {
        \App\Setting::insert(
            [
                ['key'=>'general_currency_symbol','type'=>'text','value'=>'$'],
            ]);
    }

}
