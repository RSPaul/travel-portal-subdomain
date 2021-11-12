<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HotelBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $hotel_data;
    public $file_path;
    public $file_name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking,$hotel_data, $file_path, $file_name)
    {
        $this->booking = $booking;
        $this->hotel_data = $hotel_data;
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
        return $this->view('emails.bookings.hotel-booking')
            ->attach($this->file_path, [
                    'as' => $this->file_name,
                    'mime' => 'application/pdf',
                ])
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your Hotel Booking is Confirmed - ' . $this->booking->request_data['hotelName'] . ', ' . $this->booking->booking_id)
            ->with(['booking'=>$this->booking, 'hotel_data'=>$this->hotel_data]);

    }
}
