<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $customer_detail;
    public $packet_detail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $customer_detail, $packet_detail)
    {
        //
        $this->title = $title;
        $this->customer_detail= $customer_detail;
        $this->packet_detail = $packet_detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
            ->view('mail.customer_mail');
    }
}
