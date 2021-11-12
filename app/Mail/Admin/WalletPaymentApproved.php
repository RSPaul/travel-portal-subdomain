<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WalletPaymentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $amount)
    {
        $this->user=$user;
        $this->amount=$amount;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.wallet-payment-approved')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Payment Added to Wallet')
            ->with(['user'=> $this->user, 'amount' => $this->amount]);

    }
}
