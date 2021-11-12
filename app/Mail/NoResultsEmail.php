<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoResultsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;
    public $responseData;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($requestData,$responseData)
    {
        $this->requestData=$requestData;
        $this->responseData = $responseData;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.no-results-hotel')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - No Results - Hotel Search')
            ->with(['requestData'=> $this->requestData, 'responseData'=>$this->responseData]);

    }
}
