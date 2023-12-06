<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Gloudemans\Shoppingcart\Cart;
use App\Models\Notification;
use View;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::failing(function (JobFailed $event) {
            mail(env('APP_ADMIN_EMAIL'), "Job Queue Failed", "exception: " . $event->exception);
        });
        Paginator::useBootstrap();
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');


        {
            View::composer('*', function ($view) {
                if (auth()->check()) {

                  $notifications="";
                if (\Auth::user()->last_notification_check != "") {
                    $notifications = Notification::where('user_id', '=', \Auth::user()->id)->where('created_at', '>', \Auth::user()->last_notification_check)->orderBy('id', 'desc')->get();
                }
                $cart_count = \Cart::count();
                $view->with('notifications', $notifications)->with('cart_count', $cart_count);


            }
          });
        }
    }
}
