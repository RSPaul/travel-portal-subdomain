<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $activity_data;
    public $payments;
    public $file_path;
    public $file_name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking,$activity_data, $payments, $file_path, $file_name)
    {
        $this->booking = $booking;
        $this->activity_data = $activity_data;
        $this->payments = $payments;
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
        return $this->view('emails.bookings.activity-booking')
            // ->attach($this->file_path, [
            //         'as' => $this->file_name,
            //         'mime' => 'application/pdf',
            //     ])
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Tripheist - Your Activity Booking is Confirmed - ' . $this->booking->booking_id)
            ->with(['booking'=>$this->booking, 'activity_data'=>$this->activity_data, 'payments' => $this->payments]);

    }
}
