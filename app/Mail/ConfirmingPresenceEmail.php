<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmingPresenceEmail extends Mailable
{
	use Queueable, SerializesModels;

	public $event;
	public $email;
	public $name;
	public $contact_id;
	public $from_name;

	/**
	 * Create a new message instance.
	 *
	 * @param Event $event
	 * @param $email
	 * @param $name
	 */
	public function __construct(Event $event, $email, $name, $contactId)
	{
		$this->event = $event;
		$this->email = $email;
		$this->name = $name;
		$this->contact_id = $contactId;
		$this->from_name = $event->user->getFullName();
		$this->subject = __('Presence at event confirmed');
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$name = $this->from_name;
        $fromName = env('MAIL_FROM_NAME');
        if(isset($name)) {
            $fromName = $name;
        }

		return $this
			->with(['subject' => $this->subject, 'contact_id' => $this->contact_id])
			->from(env('MAIL_FROM_ADDRESS'), $fromName)
			->to($this->email, $this->name)
			->view('email.confirming_presence');
	}
}
