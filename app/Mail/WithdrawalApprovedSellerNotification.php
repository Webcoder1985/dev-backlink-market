<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalApprovedSellerNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params = array())
    {
        //user_fullname = firstname lastname
        //amount
        //paypal_email
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Withdrawal Approved! You just received ' . $this->params['amount'] . Config::get('app.currency_symbol'))->view('email.WithdrawalApprovedSellerNotification')->with(['params' => $this->params]);
    }
}
