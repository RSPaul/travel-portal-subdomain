<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CabsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $searchData;
    public $cabs;
    public $agent;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($searchData,$cabs,$agent)
    {
        $this->searchData = $searchData;
        $this->cabs = $cabs;
        $this->agent = $agent;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.cabs')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your itineraries for Cabs')
            ->with(['searchData'=>$this->searchData,'cabs'=>$this->cabs, 'agent' => $this->agent]);

    }
}
