<?php
namespace App\Lib;

use App\Ip;
use App\Template;
use Illuminate\Support\Facades\Validator;

class Helpers {

    static public function bootProviders(){
        //ensure a template is installed
        if(!Template::where('enabled',1)->first()){
            $template = Template::first();
            $template->enabled =1;
            $template->save();
        }
        validateFolder(CANDIDATE_FILES);
        validateFolder(CANDIDATES);
        validateFolder(EDITOR_IMAGES);
        validateFolder(EMPLOYER_FILES);
        validateFolder(SETTINGS);
        validateFolder(USER_FILES);
        validateFolder(COMMENT_ATTACHMENTS);
        validateFolder(EMAIL_FILES);
        validateFolder(BLOG_FILES);
        validateFolder(TEMPLATE_PATH);
        validateFolder(TEMPLATE_FILES);
        validateFolder(PENDING_USER_FILES);

        if(!file_exists(TEMP_DIR)){
            rmkdir(TEMP_DIR);
        }

        Helpers::syncLanguage();



    }

    static public function syncLanguage(){
        //sync language files
        //check if language file exists for current template
        $currentTemplate = currentTemplate();
        if(!$currentTemplate){
            return false;
        }

        $langFile= './templates/'.$currentTemplate->directory.'/lang.php';
        // $laguage = getL
        //check for installed lang file
        $language = setting('config_language');
        $tempLang = '../resources/lang/en/temp_'.$currentTemplate->directory.'.php';

        if(!file_exists($langFile)){
             return false;
        }

        //check if tempLang is installed already
        if(!file_exists($tempLang)){
            copy($langFile,$tempLang);
            return true;
        }

        //now check if there are any changes in file modified time. Copy if so
        if(filemtime($langFile) > filemtime($tempLang)){
            unlink($tempLang);
            copy($langFile,$tempLang);
            return true;
        }




    }

     static public function getCountry(){
        $ip_address = Helpers::getClientIp();

         $data = ['ip'=>$ip_address];
         $validator = Validator::make($data,[
             'ip'=>'ip'
         ]);

         if($validator->fails()){

             return 'us';
         }

         if(env('APP_ENV','production')=='production'){

             $validator = Validator::make($data,[
                 'ip'=>'unique:ips'
             ]);

             if(!$validator->fails()){
                 //create ip record in db
                 $country = file_get_contents("http://ipinfo.io/$ip_address/country");

                 $country = trim(strtolower($country));

              //   notifyAdmin('country fetched',$ip_address.' . line 31: '.$country);

                 if(empty($country) || strlen($country)!=2){
                     $country = 'us';
                 }


                  Ip::create(['ip'=>$ip_address,'country'=>$country]);
                 return $country;
             }
             else{

                $ipModel = Ip::where('ip',$ip_address)->first();
                return $ipModel->country;
             }


         }
         else{

                return 'us';
         }

    }

    static public function getClientIp() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    static public function isBot(){

    $bots = array(
        'Googlebot', 'Baiduspider', 'ia_archiver',
        'R6_FeedFetcher', 'NetcraftSurveyAgent', 'Sogou web spider',
        'bingbot', 'Yahoo! Slurp', 'facebookexternalhit', 'PrintfulBot',
        'msnbot', 'Twitterbot', 'UnwindFetchor',
        'urlresolver', 'Butterfly', 'TweetmemeBot' );


    foreach($bots as $b){

        if( stripos( $_SERVER['HTTP_USER_AGENT'], $b ) !== false ) return true;

    }



    return false;

}

    static public function sendMail($to,$subject,$message){
        $mail = new Mail();
        $mail->setSender(env('APP_NAME'));
        $mail->setFrom(env('APP_EMAIL'));
        $mail->setTo($to);
        $mail->setSubject($subject);
        $mail->setHtml($message);
        $mail->send();
    }

}
