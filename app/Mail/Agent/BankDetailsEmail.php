<?php

namespace App\Mail\Agent;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BankDetailsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $bankDetails;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$bankDetails)
    {
        $this->user = $user;
        $this->bankDetails = $bankDetails;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.agent.bank-details')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - New Bank Account Added')
            ->with(['user'=>$this->user,'bankDetails'=>$this->bankDetails]);

    }
}
