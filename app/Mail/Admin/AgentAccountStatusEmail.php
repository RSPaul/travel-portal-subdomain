<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AgentAccountStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;
    public $link;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$link, $status)
    {
        $this->user=$user;
        $this->link=$link;
        $this->status = $status;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.agent-status')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Agent Account ' . $this->status)
            ->with(['user'=> $this->user, 'link'=>$this->link, 'status' => $this->status]);

    }
}
