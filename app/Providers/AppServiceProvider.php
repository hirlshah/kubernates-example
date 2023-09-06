<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        if(config('app.env') === 'production' || config('app.env') === 'staging') {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }

        if(config('app.env') != 'staging') {
            Event::listen(MigrationsStarted::class, function (){
                DB::statement('SET SESSION sql_require_primary_key=0');
            });

            Event::listen(MigrationsEnded::class, function (){
                DB::statement('SET SESSION sql_require_primary_key=1');
            });
        }
        
        if(isset($_SERVER['HTTP_HOST'])) {
            $url = $_SERVER['HTTP_HOST'];
            $explodedUrl = explode('.', $url);

            if(isset($explodedUrl[0]) && ($explodedUrl[0] == (env('APP_ENV') == 'production' ? 'app' : 'rank-up'))) {
                config()->set('app.name', 'RankUp');
                if(env('APP_ENV') == 'production') {
                    config()->set('app.url', 'https://app.rankup.io/');
                } else {
                    config()->set('app.url', 'https://rank-up.stage.eugeniuses.com');
                }
                config()->set('app.rankup.main_seller_email', 'seller@dev.com');
                config()->set('app.rankup.comapny_name', 'rankup');
                config()->set('app.rankup.company_logo_path', 'company_logo/rank-up-logo.svg');
                config()->set('app.rankup.company_thumbnail_path', 'thumbnail-img/rank-up-thumbnail-img.png');
                config()->set('app.rankup.company_favicon', 'company_favicon/favicon.svg');
                config()->set('app.rankup.company_title', 'Rank Up');
                config()->set('app.rankup.company_second_color', '#800080');
                config()->set('app.rankup.company_primary_color', '#56B2FF');
                config()->set('app.rankup.company_css_file', 'css/app.css');
                config()->set('app.rankup.company_default_image_file', 'assets/images/default_event.png');
                config()->set('app.rankup.location', 'Quebec, CA');
                config()->set('app.rankup.support_email', 'support@rankupacademy.ca');
                
                config()->set('database.connections.mysql', config('database.connections.rankup'));
            } elseif (isset($explodedUrl[0]) && $explodedUrl[0] == 'ibuumerang') {
                config()->set('app.name', 'Ibuumerang');
                if(env('APP_ENV') == 'production') {
                    config()->set('app.url', 'https://ibuumerang.rankup.io/');
                } else {
                    config()->set('app.url', 'https://ibuumerang.stage.eugeniuses.com');
                }
                config()->set('app.rankup.main_seller_email', '');
                config()->set('app.rankup.comapny_name', 'ibuumerang_rankup');
                config()->set('app.rankup.company_logo_path', 'company_logo/ibuumerang.png');
                config()->set('app.rankup.company_thumbnail_path', 'thumbnail-img/ibuumerang-thumbnail-img.png');
                config()->set('app.rankup.company_favicon', 'company_favicon/ibuumerang-favicon.png');
                config()->set('app.rankup.company_title', 'Ibuumerang');
                config()->set('app.rankup.company_second_color', '#ffd200');
                config()->set('app.rankup.company_primary_color', '#0892d0');
                config()->set('app.rankup.company_css_file', 'css/ibuumerang.css');
                config()->set('app.rankup.company_default_image_file', 'assets/images/default_event_ibuumerang.png');
                config()->set('app.rankup.location', 'Houston, TX');
                config()->set('app.rankup.support_email', 'support@ibuumerang.com');
                
                config()->set('database.connections.mysql', config('database.connections.ibuumerang_rankup'));
            }
        }

        if(empty($_SESSION['timezone_offset']) && !empty($_COOKIE['timezone_offset'])){
            $_SESSION['timezone_offset'] = $_COOKIE['timezone_offset'];
        }
        if(empty($_SESSION['timezone_name']) && !empty($_COOKIE['timezone_name'])){
            $_SESSION['timezone_name'] = $_COOKIE['timezone_name'];
        }
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191); 
    }
}
