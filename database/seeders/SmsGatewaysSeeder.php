<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SmsGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
}
