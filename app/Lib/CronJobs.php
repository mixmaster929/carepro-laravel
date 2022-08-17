<?php
namespace App\Lib;

use App\Email;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CronJobs
{
    use HelperTrait;
    public function deleteTempFiles(){
        $path = 'storage/tmp';

        $objects = scandir($path);
        foreach ($objects as $object) {
             $dir = $path.'/'.$object;
            if(is_dir($dir) && $object !='..' && $object !='.'){
                if (filemtime($dir) < time() - 86400) {
                    deleteDir($dir);
                }
            }

        }
    }

    public function sendPendingEmails(){
        //send all emails that are pending
        $emails = Email::where('sent',0)->where('send_date','<=',\Illuminate\Support\Carbon::now()->toDateString())->limit(500)->get();

        foreach($emails as $email){
                $this->sendSavedEmail($email);
        }


    }

    /**
     * Sends a reminder to employers before an interview
     */
    public function sendInterviewReminder(){

    }

    public function sendInterviewFeedbackPrompt(){

    }

}