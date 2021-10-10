<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\Helper;

class HowDidWeDo extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.how-did-we-do';
    
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($user)
    {
        $this->user    = $user;
        $this->subject = 'How did you find ' . env('APP_NAME') . '? - What can we do to improve?';
        
        $this->email = Helper::createNewEmail([
            'toEmail'   => $this->user->email,
            'toName'    => $this->user->username,
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
