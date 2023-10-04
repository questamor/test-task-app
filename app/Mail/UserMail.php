<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $content;

    public function __construct(array $content) {
        $this->content = $content;
    }

    public function composeEmailData(string $subject, string $body) {
        return [
            'subject' => 'Исключение из группы',
            'body' => "Здравствуйте, $this->user->name! Истекло время вашего участия в группе $this->group->name"
        ];;
    }

    public function build()
    {
        return $this->subject($this->content['subject'])
            ->view('emails.main');
    }
}
