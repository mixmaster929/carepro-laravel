<?php

namespace App\Mail;

use App\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewAlert extends Mailable
{
    use Queueable, SerializesModels;
    public $interview;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(setting('general_admin_email'),setting('general_site_name'))->subject(__('site.upcoming-interview'))->view('mails.interview');
    }
}
