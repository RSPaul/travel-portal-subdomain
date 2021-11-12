<?php

namespace App\Mail\Agent;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WalletPaymentRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $file_path;
    public $file_name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$amount,$file_path,$file_name)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->file_path = $file_path;
        $this->file_name = $file_name;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.agent.wallet-payment-request')
            ->attach($this->file_path, [
                'as' => $this->file_name,
                'mime' => 'application/pdf',
            ])
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Confirmation for Wire Transfer')
            ->with(['user'=>$this->user,'amount'=>$this->amount]);

    }
}
