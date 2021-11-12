<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlightsHotelsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $searchData;
    public $hotels;
    public $agent;
    public $flights1;
    public $flights2;
    public $url;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($searchData,$hotels, $agent, $flights1, $flights2, $url)
    {
        $this->searchData = $searchData;
        $this->hotels = $hotels;
        $this->agent = $agent;
        $this->flights1 = $flights1;
        $this->flights2 = $flights2;
        $this->url = $url;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.flights-hotels')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your itineraries for Flight & Hotel')
            ->with(['searchData'=>$this->searchData,'hotels'=>$this->hotels, 'agent' => $this->agent, 'flights1' => $this->flights1, 'flights2' => $this->flights2, 'url' => $this->url]);

    }
}
