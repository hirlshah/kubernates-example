<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendEmailInQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;

class QueueEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data_array)
    {
        $this->data = $data_array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locale = app()->getLocale();
        if(isset($this->data['locale']) && !empty($this->data['locale'])) {
            $locale = $this->data['locale'];
        }
        $email = new SendEmailInQueue($this->data);
        Mail::to($this->data['email_to'])->locale($locale)->send($email);
    }
}
