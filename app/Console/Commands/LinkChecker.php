<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Links;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\LinkDownSellerNotification;
use App\Mail\SiteDownSellerNotification;
use App\Mail\LinkDownAdminNotification;
use Storage;

class LinkChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:linkchecker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link Checker';

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

      $date_24 = \Carbon\Carbon::now()->subHours(24);
      $links = Links::where('status','=', 20)->where(function ($links) use($date_24) {  return $links->whereNull('last_link_live_check')->orWhere('last_link_live_check', '<', $date_24); })->get();

      //links recheck
      $date_6 = \Carbon\Carbon::now()->subHours(6);
      $links_6 = Links::where('status','=', 20)->where('offline_counter','=', 1)->where('last_link_live_check', '<', $date_6)->get();


      $links=$links->merge($links_6);
      $seller_mail_contents=[];


      foreach($links as $link)
      {
        $response_http_code=get_http_code($link->page_url);
        $link->last_http_status_code=$response_http_code['httpCode'];
        $link->last_link_live_check=\Carbon\Carbon::now();
        if($response_http_code['httpCode']!=200)
        {
          echo "Status : 200"."\n";
          $notification = new Notification();
          $notification->user_id=$link->seller_id;
          $notification->type="Link ".$link->page_url." found down";
          $notification->save();
          $link->offline_counter+=1;
          if($response_http_code['httpCode']==404)
          {
            $response_site_http_code=get_http_code($link->page->site->site_url);
          }
        }
        else{
          $found=check_link_added_to_blog($response_http_code['content'],$link->promoted_url);
          if(!$found)
          {
            $notification = new Notification();
            $notification->user_id=$link->seller_id;
            $notification->type="Link ".$link->promoted_url." not found on ".$link->page_url;
            $notification->save();
            echo "Link not found"."\n";
            $link->offline_counter+=1;
            add_links_seller_blog($link->page_id);
          }
          else
            $link->offline_counter=0;
        }
        $link->update();
        if($link->offline_counter>1){
          //Code to combine all issues seller wise to prevent multiple mails
          $seller_mail_contents['seller'][$link->seller_id]['site'][$link->page->seller_site_id]['site_url']=$link->page->site->site_url;
          $seller_mail_contents['seller'][$link->seller_id]['site'][$link->page->seller_site_id]['link'][$link->id]['offline_counter']=$link->offline_counter;
          if(isset($response_site_http_code['httpCode']) && $response_site_http_code['httpCode']==404){
            $seller_mail_contents['seller'][$link->seller_id]['site'][$link->page->seller_site_id]['site_status']=404;
          }
          else {
            $seller_mail_contents['seller'][$link->seller_id]['site'][$link->page->seller_site_id]['link'][$link->id]['url']=$link->page_url;
          }
        }
      }

      //Write code to Send mail based on $seller_mail_contents
      $linkcheck_reports_admin=array();
      if(isset($seller_mail_contents['seller'])){
        foreach($seller_mail_contents['seller'] as $seller_id => $seller_sites){
          $user=User::find($seller_id);

          foreach ($seller_sites['site'] as $seller_site){
            $linkcheck_reports_admin['site_url']="";
            if(isset($seller_site['site_status']) && $seller_site['site_status']==404){
              $params=array();
              $params['user_fullname']=$user->firstname." ".$user->lastname;
              $params['site_url']=$seller_site['site_url'];

              $linkcheck_reports_admin['site_url'].=$seller_site['site_url']."<br />";

              Mail::to($user->email)->send(new SiteDownSellerNotification($params));
              continue;
            }else{
              $params=array();
              $mail_content="";
              $linkcheck_reports_admin['page_url']="";
              $params['site_url']=$seller_site['site_url'];
              foreach($seller_site['link'] as $link){
                $mail_content.=$link['url']."<br />";

                $linkcheck_reports_admin['page_url'].=$link['url']."<br />";
              }

              if($mail_content!=""){
                $params['user_fullname']=$user->firstname." ".$user->lastname;
                $params['mail_content']=$mail_content;
                Mail::to($user->email)->send(new LinkDownSellerNotification($params));
              }
            }
          }
        }

        if(!empty($linkcheck_reports_admin)){
          Mail::to(config('app.admin_email'))->send(new LinkDownAdminNotification($linkcheck_reports_admin));
        }
      }
      return 0;
    }
}
