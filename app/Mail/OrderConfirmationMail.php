<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderData;
    public $userEmail;

    public function __construct($orderData, $userEmail)
    {
        $this->orderData = $orderData;
        $this->userEmail = $userEmail;
    }

    public function build()
    {
        return $this->subject('Order Confirmation from Adik Cosmetics')
                    ->view('emails.order_confirmation')
                    ->with([
                        'orderData' => $this->orderData,
                        'userEmail' => $this->userEmail,
                    ]);
    }
}
