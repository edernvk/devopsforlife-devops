<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductNotFoundMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = auth()->user();

        return $this->markdown('emails.product-not-found-mail')
        ->from(config('mail.from'))
        ->to(config('mail.to'))
        ->cc($this->data['users'])
        ->subject('[CDC DIGITAL] ' . $this->data['subject'])
        ->with([
            'user' => 'O usuário ' . $user->name . ' enviou a seguinte mensagem:',
            'message' => $this->data['message']
        ]);
    }
}
