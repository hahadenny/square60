<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\SearchResults;
use App\EstateInfo;
use Illuminate\Support\Facades\Request;


class ContactListingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {

        $userEmail = $request->post('email');
        $userPhone = $request->post('phone');

        $userMessage = $request->post('message_to_agent');


        return $this->view('emails.results',compact('userMessage','userPhone','userEmail'));
    }

}
