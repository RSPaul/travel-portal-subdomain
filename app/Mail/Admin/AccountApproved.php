<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user=$user;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.account-approved')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Bank Account Approved')
            ->with(['user'=> $this->user]);

    }
}
