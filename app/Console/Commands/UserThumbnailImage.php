<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\Helper\CommonUtil;
use App\Models\User;

class UserThumbnailImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all user profile images thumbnails';

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
        $users = User::whereNotNull('profile_image')->get();
        $ext = ['png' , 'jpg' , 'jpeg'];
        
        foreach ($users as $user){
            if (!empty($user->profile_image) && is_null($user->thumbnail_image)) {
                $profile_image =  $user->profile_image;
                $extName = strtolower(pathinfo($profile_image, PATHINFO_EXTENSION));   
                if (in_array($extName, $ext)) {
                    $explodedOptImageName = explode('/', $user->profile_image);
                    $new_media_name = CommonUtil::uploadThumbnailFileToFolderScript($user->profile_image, 'users/thumbnails', end($explodedOptImageName),$extName );
                    $user->thumbnail_image = $new_media_name;
                    $user->save();
                }
            }
        }
        return true;
    }
}
