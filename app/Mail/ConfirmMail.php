<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\Helper;

class ConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailConfirmation;
    public $email;
    public $subject;

    const EMAIL_TYPE = 'emails.confirm-email';
    
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($emailConfirmation)
    {
        $this->emailConfirmation = $emailConfirmation;

        if($this->emailConfirmation->type == 'user-confirmation') {
            $this->subject = 'Welcome to ' . env('APP_NAME') . ' - Please confirm your email to get started!';
        } 

        // else if($this->emailConfirmation->type == 'site-member-confirmation') {
        //     $this->subject = 'Welcome to ' . json_decode($emailConfirmation->data, true)['siteName'] . ' - Please confirm your email to get started!';
        // }

        // else if($this->emailConfirmation->type == 'site-subscriber-confirmation') {
        //     $this->subject = 'Welcome to ' . json_decode($emailConfirmation->data, true)['siteName'] . '\'s newsletter - Please confirm your email to get started!';
        // }

        // else if($this->emailConfirmation->type == 'site-sale-confirmation') {
        //     $this->subject = 'Thank you for your interest in ' . json_decode($emailConfirmation->data, true)['siteName'] . ' - Please confirm your email to get started!';
        // }

        // else if($this->emailConfirmation->type == 'site-feedback-confirmation') {
        //     $this->subject = 'Thank you for your feedback in ' . json_decode($emailConfirmation->data, true)['siteName'] . ' - Please confirm your email before we can send it.';
        // }
        
        $this->email = Helper::createNewEmail([
            'toEmail'      => $this->emailConfirmation->to_email,
            'toName'       => $this->emailConfirmation->to_name,
            'fromName'     => $this->emailConfirmation->from_name,
            'replyToEmail' => $this->emailConfirmation->reply_to_email,
            'subject'      => $this->subject,
            'emailType'    => self::EMAIL_TYPE,
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        Helper::markEmailAsSent($this->email);
        return $this->from(config('mail.from.address'), $this->emailConfirmation->from_name)
                    ->to($this->emailConfirmation->to_email, $this->emailConfirmation->to_name)
                    ->replyTo($this->emailConfirmation->reply_to_email)
                    ->subject($this->subject)
                    ->view(self::EMAIL_TYPE);
    }
}
