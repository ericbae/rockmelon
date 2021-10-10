<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\Helper;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $total;
    public $available;
    public $expired;
    public $expire7;
    public $expire30;
    public $others;

    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.newsletter';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $total, $available, $expired, $expire7, $expire30, $others)
    {
        $this->user      = $user;
        $this->total     = $total;
        $this->available = $available;
        $this->expired   = $expired;
        $this->expire7   = $expire7;
        $this->expire30  = $expire30;
        $this->others    = $others;
        $this->subject   = 'Latest report on your domains at ' . env('APP_NAME');
        
        $this->email = Helper::createNewEmail([
            'toEmail'   => $this->user->email,
            'toName'    => $this->user->email,
            'subject'   => $this->subject,
            'emailType' => self::EMAIL_TYPE,
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        Helper::markEmailAsSent($this->email);
        return $this->subject($this->subject)->view(self::EMAIL_TYPE);
    }
}