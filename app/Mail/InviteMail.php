<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\Helper;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $toEmail;
    public $site;
    public $user;
    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.invite';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($toEmail, $site, $user)
    {
        $this->toEmail = $toEmail;
        $this->site    = $site;
        $this->user    = $user;
        $this->subject = 'You are invited to ' . $site->name;

        $this->email = Helper::createNewEmail([
            'toEmail'      => $toEmail,
            'toName'       => $toEmail,
            'subject'      => $this->subject,
            'emailType'    => self::EMAIL_TYPE,
            'siteId'       => $site->id
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        Helper::markEmailAsSent($this->email);
        return $this->from(config('mail.from.address'), $this->site->name)->subject($this->subject)->view(self::EMAIL_TYPE);
    }
}
