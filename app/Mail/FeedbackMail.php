<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Helpers\Helper;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $fromEmail;
    public $feedback;
    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.feedback';


    /**
     * Create a new feedback instance.
     *
     * @return void
     */
    public function __construct($fromEmail, $feedback)
    {
        $this->user      = User::where('email', $fromEmail)->first();
        $this->fromEmail = $fromEmail;
        $this->feedback  = $feedback;
        $this->subject   = 'Newsy feedback from - ' . $fromEmail;

        $this->email = Helper::createNewEmail([
            'toEmail'      => 'hello@newsy.co',
            'toName'       => 'Eric',
            'replyToEmail' => $fromEmail,
            'subject'      => $this->subject,
            'emailType'    => self::EMAIL_TYPE,
        ]);
    }

    /**
     * Build the feedback.
     *
     * @return $this
     */
    public function build() {
        Helper::markEmailAsSent($this->email);
        return $this->subject($this->subject)->view(self::EMAIL_TYPE);
    }
}