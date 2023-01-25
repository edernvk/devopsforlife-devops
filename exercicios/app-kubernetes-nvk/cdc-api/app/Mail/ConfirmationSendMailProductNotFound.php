<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationSendMailProductNotFound extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = auth()->user();

        return $this->markdown('emails.confirmation-send-mail')
            ->subject('[CDC DIGITAL] Seu email foi enviado com sucesso')
            ->from(config('mail.from'))
            ->to($user)
            ->with([
                'user' => $user
            ]);
    }
}
