<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\SearchResults;
use App\EstateInfo;


class ResultMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $results;
    public $subject='';
    public $domain='';
    public $lastmail='';
    public $search_id='';

    public function __construct($results, $domain, $subject, $lastmail='', $search_id='')
    {
        $this->results = $results;
        $this->subject = $subject;
        $this->domain = $domain;
        $this->lastmail = $lastmail;
        $this->search_id = $search_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  
        if ($this->subject)
            return $this->subject($this->subject)->view('emails.results')->with('domain', $this->domain)->with('subject', $this->subject)->with('lastmail', $this->lastmail)->with('search_id', $this->search_id);
        else    
            return $this->view('emails.results')->with('domain', $this->domain)->with('subject', $this->subject)->with('lastmail', $this->lastmail)->with('search_id', $this->search_id);
    }


}
