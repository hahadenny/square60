<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $domain;
    public $type;
    public $subject;
    public $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $domain, $type, $subject, $msg)
    {
        $this->data = $data;
        $this->domain = $domain;
        $this->type = $type;
        $this->subject = $subject;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.failed')->subject($this->subject);
    }
}
