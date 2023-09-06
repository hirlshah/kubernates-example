<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ScriptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'You can write any code here to run directly on server.';

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
            $user = User::on($connection)->find(User::MAIN_SELLER_ID);
            if ($user) {
                $users = User::on($connection)->whereNull('parent_id')->where('id', '!=', User::MAIN_SELLER_ID)->whereHas(
                        'roles', function ($q) {
                            $q->where('name', 'Seller');
                        }
                    )->update([
                        'parent_id' => $user->id,
                        'root_id' => $user->id,
                    ]);
            }
            return true;
        } 
    }
}
