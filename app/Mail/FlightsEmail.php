<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlightsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $searchData;
    public $flights;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($searchData,$flights)
    {
        $this->searchData = $searchData;
        $this->flights = $flights;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.flights')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your itineraries for Flights')
            ->with(['searchData'=>$this->searchData,'flights'=>$this->flights]);

    }
}
