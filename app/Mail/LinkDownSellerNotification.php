<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LinkDownSellerNotification extends Mailable
{
    use Queueable, SerializesModels;


    protected $params;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params=array())
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Some url(s) of your site '.$this->params['site_url'].' may be down')->view('email.linkDownSellerNotification')->with(['params' => $this->params]);
    }
}
