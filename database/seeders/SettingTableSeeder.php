<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
}
