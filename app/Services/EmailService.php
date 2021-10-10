<?php 

namespace App\Services;
use Log, DB, Exception, Cache, Mail;
use App\Models\Email;
use App\Models\EmailConfirmation;
use App\Mail\ConfirmMail;

class EmailService {

	public function alreadyConfirmed($email) {
		return EmailConfirmation::where('to_email', $email)->whereNotNull('confirmed_at')->exists();
	}

	public function confirmEmail($type, $toEmail, $toName, $fromName, $replyToEmail, $webhookUrl, $data) {
		$ec                 = new EmailConfirmation;
		$ec->type           = $type;
		$ec->to_email       = $toEmail;
		$ec->to_name        = $toName;
		$ec->from_name      = $fromName;
		$ec->reply_to_email = $replyToEmail;
		$ec->webhook_url    = $webhookUrl;
		$ec->data           = json_encode($data);
		$ec->save();
		Mail::to($toEmail)->queue(new ConfirmMail($ec));
	}
}