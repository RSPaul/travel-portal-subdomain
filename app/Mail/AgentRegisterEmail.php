<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AgentRegisterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $link;
    public $file_path;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password,$link,$file_path)
    {
        $this->user=$user;
        $this->password = $password;
        $this->link=$link;
        $this->file_path=$file_path;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.agent-register')
                ->attach($this->file_path, [
                    'as' => 'AffiliateProgramAgreement.pdf',
                    'mime' => 'application/pdf',
                ])
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - New Agent Account')
            ->with(['user'=> $this->user, 'password'=>$this->password, 'link'=>$this->link]);

    }
}
