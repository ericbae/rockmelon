<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Site;
use App\Models\Member;
use App\Helpers\Helper;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;
    public $fromEmail;
    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.contact-us';


    /**
     * Create a new feedback instance.
     *
     * @return void
     */
    public function __construct($feedback, $fromEmail)
    {
        $this->feedback  = $feedback;
        $this->fromEmail = $fromEmail;
        $this->subject   = 'Message received from web via user email ' . $fromEmail;

        $this->email = Helper::createNewEmail([
            'toEmail'      => config('mail.from.address'),
            'toName'       => config('mail.from.name'),
            'replyToEmail' => $fromEmail,
            'replyToName'  => null,
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
        return $this->from(config('mail.from.address'), env('APP_NAME'))->subject($this->subject)->view(self::EMAIL_TYPE);
    }
}