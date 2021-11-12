<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MultiCardPayment extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $payment;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$payment)
    {
        $this->user = $user;
        $this->payment = $payment;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.multicard-payment')
            ->from($this->user->email)
            ->subject('Tripheist - Your payment done successfully - #' . $this->payment->txn_id)
            ->with(['user'=>$this->user,'payment'=>$this->payment]);

    }
}
