<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class resetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $token;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$token,$email)
    {
        $this->name = $name;
        $this->token=$token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.resetPassword');
    }
}
