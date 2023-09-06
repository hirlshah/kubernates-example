<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Classes\Helper\ReferralCode;

class MakeReferralCodeForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MakeReferralCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Referral Code If Not Exits';

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
            $users = User::on($connection)->whereNull('referral_code')->get();
            foreach ($users as $key => $user) {
                $referralCode = new ReferralCode();
                $user->referral_code = $referralCode->createReferralCode();
                $user->save();
            }
        }
    }
}
