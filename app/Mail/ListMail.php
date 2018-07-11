<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class ListMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $result;
    public $agents;
    public $subject='';
    public $domain='';

    public function __construct($result, $agents, $domain, $subject='')
    {
        $this->result = $result;
        $this->agents = $agents;
        $this->subject = $subject;
        $this->domain = $domain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  
        if ($this->subject)
            return $this->subject($this->subject)->view('emails.list')->with('domain', $this->domain)->with('subject', $this->subject)->with('result'. $this->result)->with('agents'. $this->agents);
        else    
            return $this->view('emails.list')->with('domain', $this->domain)->with('subject', $this->subject)->with('result', $this->result)->with('agents'. $this->agents);
    }


}
