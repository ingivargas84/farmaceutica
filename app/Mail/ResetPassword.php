<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $nuevopassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $nuevopassword)
    {
        $this->user = $user;
        $this->nuevopassword = $nuevopassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('mails.resetpassword');

        return $this->markdown('mails.resetpassword');
    }
}
