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
    public $pdfPath;

    public function __construct($orderData, $userEmail, $pdfPath)
    {
        $this->orderData = $orderData;
        $this->userEmail = $userEmail;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Order Confirmation from Adik Cosmetics')
                    ->view('emails.order_confirmation')
                    ->with([
                        'orderData' => $this->orderData,
                        'userEmail' => $this->userEmail,
                    ])
                    ->attach($this->pdfPath, [
                        'as' => 'invoice_' . $this->orderData['invoice_no'] . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
