<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserRegister extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password)
    {
        $this->user=$user;
        $this->password = $password;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.user-register')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - New User Account')
            ->with(['user'=> $this->user, 'password'=>$this->password]);

    }
}
