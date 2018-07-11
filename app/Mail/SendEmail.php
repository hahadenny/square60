<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $domain;

    public function __construct($data, $domain)
    {
        $this->data = $data;
        $this->domain = $domain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->data['subject']) && $this->data['subject'])
            return $this->subject($this->data['subject'])->view('emails.send')->with('domain', $this->domain)->replyTo($this->data['useremail'], $this->data['useremail']);
        else    
            return $this->view('emails.send')->with('domain', $this->domain)->replyTo($this->data['useremail'], $this->data['useremail']);
    }
}
