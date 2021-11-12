<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HotelsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $searchData;
    public $hotels;
    public $agent;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($searchData,$hotels, $agent)
    {
        $this->searchData = $searchData;
        $this->hotels = $hotels;
        $this->agent = $agent;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.hotels')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your itineraries for Hotels')
            ->with(['searchData'=>$this->searchData,'hotels'=>$this->hotels, 'agent' => $this->agent]);

    }
}
