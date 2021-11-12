<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $temp_password;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$temp_password)
    {
        $this->user=$user;
        $this->temp_password = $temp_password;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.test')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - New User Account')
            ->with(['user'=> $this->user, 'temp_password'=>$this->temp_password]);

    }
}
