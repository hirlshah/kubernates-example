<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailInQueue extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $fromName = env('MAIL_FROM_NAME');
        if(isset($data['from_name'])) {
            $fromName = $data['from_name'];
        }

        return $this->view($this->data['template'])
            ->from(env('MAIL_FROM_ADDRESS'), $fromName)
            ->subject($this->data['subject'])
            ->with($data);
    }
}
