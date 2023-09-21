<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Lang;

class BroadCastMailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public string $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function build()
    {
        return $this->markdown('emails.broadcast')
            ->subject(Lang::get($this->subject))
            ->with([
                'contentMessage' => $this->message,
            ]);
        //        return (new MailMessage)
        //            ->subject(Lang::get($this->subject))
        //            ->line(Lang::get($this->message));
    }
}
