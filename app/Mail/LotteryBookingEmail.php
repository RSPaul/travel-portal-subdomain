<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LotteryBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $hotel_data;
    public $lottery_no;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking,$hotel_data, $lottery_id)
    {
        $this->booking = $booking;
        $this->hotel_data = $hotel_data;
        $this->lottery_no = $lottery_id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bookings.lottery-booking')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - You are successfully enrolled for Lottery System  while booking - ' . $this->booking->request_data['hotelName'] . ', ' . $this->booking->booking_id)
            ->with(['booking'=>$this->booking,'lottery_no'=>$this->lottery_no, 'hotel_data'=>$this->hotel_data]);

    }
}
