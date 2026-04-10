<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginSuccessMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $ip;
    public $time;

    public function __construct($user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->time = now()->format('d M Y H:i:s');
    }

    public function build()
    {
        return $this->subject('Login Berhasil')
                    ->view('emails.login-success');
    }
}