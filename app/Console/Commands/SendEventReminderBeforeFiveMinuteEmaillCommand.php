<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Enums\EventActive;
use Carbon\Carbon;
use App\Enums\ContactBoardStatus;
use App\Jobs\QueueEmails;
use App\Mail\SendEmailInQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class SendEventReminderBeforeFiveMinuteEmaillCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:eventRemiderBeforeFiveMinute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Event reminder before five minute email send successfully';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connections = dbConnections();
        foreach($connections as $connection) {
            $fromTime = Carbon::now();
            $toTime = Carbon::now()->addMinutes(5);

            $events = Event::on($connection)->whereBetween(DB::raw("CONCAT(meeting_date,' ',meeting_time)"), [$fromTime,$toTime])->where('is_active', EventActive::ACTIVE)->get();
               
            if($events->count() > 0) {
                foreach($events as $event) {
                    if(isset($event->reps) && !empty($event->reps)) {
                        foreach($event->reps as $eventRep) { 
                            if($eventRep->pivot->status == ContactBoardStatus::CONFIRMED_FOR_ZOOM) {
                                $subject =  __('Visite de rappel de votre événement');
                                if($eventRep->lang == "en") {
                                    $subject = __('Visit reminder of your event');
                                }
                                $data_array = [
                                    'subject' => $subject.' '.$event->name,
                                    'template' => 'email.send_event_reminder_email',
                                    'email_to' => $eventRep->email,
                                    'user_name' => $eventRep->name,
                                    'event_name' => $event->name,
                                    'locale' =>  $eventRep->lang,
                                    'verify_url' => route('frontend.event.details',$event->slug),
                                    'from_address' => $event->user->email,
                                    'from_name' => $event->user->getFullName()
                                ];
                                QueueEmails::dispatch($data_array, new SendEmailInQueue($data_array));
                                Log::info('Event reminder before five minute email send successfully '.$event->name.' '.$event->id);
                            }
                        }
                    }
                }
            }
            return Command::SUCCESS;
        }
    }
}
