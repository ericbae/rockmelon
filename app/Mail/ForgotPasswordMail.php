<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Helpers\Helper;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.forgot-password';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user    = $user;
        $this->subject = 'Reset your '. config('app.name') . ' password';
        
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
