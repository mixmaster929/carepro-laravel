<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Generic extends Mailable
{
    use Queueable, SerializesModels;


    public $subject;
    public $msg;
    public $sender;
    public $attachmentFiles = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$message,$from=null,$attachments=null)
    {
        if(empty($from)){
            $from =['address'=>setting('general_admin_email'),'name'=>setting('general_site_name')];

        }

        $this->subject = $subject;
        $this->msg = $message;
        $this->sender = $from;
        $this->attachmentFiles = $attachments;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail= $this->from($this->sender['address'],$this->sender['name'])->subject($this->subject)->view('mails.generic');

        //add attachments
        if(!empty($this->attachmentFiles)){
            if(is_array($this->attachmentFiles)){
                foreach($this->attachmentFiles as $attachment){
                    if(file_exists($attachment)){
                        $mail->attach($attachment);
                    }
                }
            }
            elseif(file_exists($this->attachmentFiles)){
                $mail->attach($this->attachmentFiles);
            }
        }

        return $mail;
    }
}
