<?php

namespace App\Providers;

use App\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Mail\TransportManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        if(!file_exists('../storage/installed')){
            return false;
        }
        if(!Schema::hasTable('settings')){
            return true;
        }

        try{
            //setup email
            $protocol = setting('mail_protocol');
            if($protocol=='smtp'){
                config([
                    'mail.driver' => 'smtp',
                    'mail.host' => setting('mail_smtp_host'),
                    'mail.port' => setting('mail_smtp_port'),
                    'mail.encryption' =>env('MAIL_ENCRYPTION','tls'),
                    'mail.username' => setting('mail_smtp_username'),
                    'mail.password' => setting('mail_smtp_password')
                ]);

               // $app = App::getInstance();

                /*     $app['swift.transport'] = $app->share(function ($app) {
                         return new TransportManager($app);
                     });*/

                /*$app->singleton('swift.transport', function ($app) {
                    return new \Illuminate\Mail\TransportManager($app);
                });*/

                $transport = new \Swift_SmtpTransport(setting('mail_smtp_host'), setting('mail_smtp_port'),env('MAIL_ENCRYPTION','tls'));
                $transport->setUsername(setting('mail_smtp_username'));
                $transport->setPassword(setting('mail_smtp_password'));



                $mailer = new \Swift_Mailer($transport);
                Mail::setSwiftMailer($mailer);
            }

            //set language
            $language = setting('config_language');
            if($language != 'en'){
                App::setLocale($language);
            }
        }
        catch(\Exexption $ex){

        }

        define('UPLOAD_PATH',config('app.upload_path'));

        //set contstants
        define('CANDIDATE_FILES','candidate_files');
        define('CANDIDATES','candidates');
        define('EDITOR_IMAGES','editor_images');
        define('EMPLOYER_FILES','employer_files');
        define('SETTINGS','settings');
        define('USER_FILES','user_files');
        define('COMMENT_ATTACHMENTS','comment_attachments');
        define('EMAIL_FILES','email_files');
        define('BLOG_FILES','blog_files');
        define('TEMP_DIR','../storage/tmp');
        define('TEMPLATE_PATH','templates');
        define('TEMPLATE_FILES','template_files');
        define('PENDING_USER_FILES','pending_files');
        define('PAYMENT_PATH','gateways/payment');
        define('MESSAGING_PATH','gateways/messaging');



        //define path to current template
        $currentTemplate = Template::where('enabled',1)->first();
        if($currentTemplate && setting('frontend_status') != '0'){

            $layout = $currentTemplate->directory.'.views.layouts.layout';
            define('TLAYOUT',$layout);

        }
        else{
            $userLayout = 'layouts.app';
           /* if (Auth::check()) {
                $user = Auth::user();
                switch($user->role_id){
                    case 1:
                        $userLayout = 'layouts.admin';
                        break;
                    case 2:
                        $userLayout = 'layouts.employer';
                        break;
                    case 3:
                        $userLayout = 'layouts.candidate';
                        break;
                }

            } */
            define('TLAYOUT',$userLayout);
        }


        View::composer('*', function($view) {



            //define path to current template
            $currentTemplate = Template::where('enabled',1)->first();
            if($currentTemplate && setting('frontend_status') != '0'){

                $layout = $currentTemplate->directory.'.views.layouts.layout';

                $view->with('templateLayout',$layout);
                $view->with('userLayout',$layout);
            }
            else{
                $userLayout = 'layouts.app';
                 if (Auth::check()) {
                     $user = Auth::user();
                     switch($user->role_id){
                         case 1:
                             $userLayout = 'layouts.admin';
                             break;
                         case 2:
                             $userLayout = 'layouts.employer';
                             break;
                         case 3:
                             $userLayout = 'layouts.candidate';
                             break;
                     }

                 }

                 if(session()->has('homeUrl')){
                     $userLayout = 'layouts.cart';
                 }

                $view->with('templateLayout',$userLayout);
                $view->with('userLayout',$userLayout);
            }





            if (Auth::check()) {
                $user = Auth::user();
                switch($user->role_id){
                    case 1:

                        $userLayout = 'layouts.admin';
                        break;
                    case 2:
                        $userLayout = 'layouts.employer';
                        break;
                    case 3:
                        $userLayout = 'layouts.candidate';
                        break;
                }

                if(session()->has('homeUrl')){
                    $userLayout = 'layouts.cart';
                }

                $view->with('userLayout',$userLayout);
            }

        });




        Blade::directive('route', function ($arguments) {
            return "<?php echo route({$arguments}); ?>";
        });

        if(class_exists('\App\Lib\Helpers') && method_exists(new \App\Lib\Helpers(),'bootProviders')){
            \App\Lib\Helpers::bootProviders();
        }

    }
}
