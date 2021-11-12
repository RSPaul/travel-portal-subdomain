<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivitiesEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $searchData;
    public $activities;
    public $agent;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($searchData,$activities,$agent)
    {
        $this->searchData = $searchData;
        $this->activities = $activities;
        $this->agent = $agent;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.activities')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your itineraries for Activities')
            ->with(['searchData'=>$this->searchData,'activities'=>$this->activities,'agent'=>$this->agent]);

    }
}
