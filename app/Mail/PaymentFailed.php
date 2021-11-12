<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentFailed extends Mailable
{
    use Queueable, SerializesModels;

    public $paymentData;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paymentData)
    {
        $this->paymentData = $paymentData;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.payment-failed')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Payment Failed')
            ->with(['paymentData'=> $this->paymentData]);

    }
}
