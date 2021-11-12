<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LotteryWonEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $lottery;
    public $ticketId;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$lottery, $ticket_id)
    {
        $this->user = $user;
        $this->lottery = $lottery;
        $this->ticketId = $ticket_id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.lottery-winner')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Congratulation!! You won the lottery for ticket - #' . $this->ticketId)
            ->with(['user'=>$this->user,'lottery'=>$this->lottery, 'ticketId'=>$this->ticketId]);

    }
}
