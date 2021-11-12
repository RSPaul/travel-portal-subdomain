<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlightBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $payments;
    public $farerules;
    public $file_path;
    public $file_name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking,$payments,$segments,$farerules, $file_path, $file_name)
    {
        $this->booking = $booking;
        $this->payments=$payments;
        $this->segments=$segments;
        $this->farerules=$farerules;
        $this->file_path = $file_path;
        $this->file_name = $file_name;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // echo "<pre>"; print_r($this->booking['request_data']);
        // print_r($this->booking['request_data']['travelData']);
        //  die();
        return $this->view('emails.bookings.flight-booking')
            ->attach($this->file_path, [
                    'as' => $this->file_name,
                    'mime' => 'application/pdf',
            ])
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Flight Booking')
            ->with(['booking' =>$this->booking, 'payments' =>$this->payments,'segments' => $this->segments, 'farerules' => $this->farerules]);

    }
}
