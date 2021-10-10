<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\Helper;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    const EMAIL_TYPE = 'emails.test';

    /**
     * Create a new msg instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->subject = 'This is a test email';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject($this->subject)->view(self::EMAIL_TYPE);
    }
}
