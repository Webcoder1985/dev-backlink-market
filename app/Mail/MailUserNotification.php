<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailUserNotification extends Mailable
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
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->params['subject'])->view('email.MailAdminNotification')->with(['params' => $this->params]);
    }
}
