<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $domain;
    public $type;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $domain, $type, $subject)
    {
        $this->data = $data;
        $this->domain = $domain;
        $this->type = $type;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.billing')->subject($this->subject);
    }
}
