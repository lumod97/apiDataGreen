<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class EmailSJ extends Mailable
{
    public $data;
    public $subject;

    public function __construct($data, $subject)
    {
        $this->data = $data;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.demoEmail');
    }
}
