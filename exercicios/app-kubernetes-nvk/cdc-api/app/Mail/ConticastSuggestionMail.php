<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ConticastSuggestionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = json_decode(json_encode($data, TRUE));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $currentUser = auth()->user();

        return $this
            ->bcc(Str::contains($currentUser->email, '@penze.com.br')
                ? ['cristiano@penze.com.br', 'tecnologia@penze.com.br']
                : [])
            ->subject('[CDC Digital] Sugestão de tema Conti Cast')
            ->markdown('emails.conticast-suggestion')
            ->with([
                'name' => $currentUser->name,
                'id' => $currentUser->id,
                'ip' => request()->ip(),
                'suggestion' => $this->data->suggestion
            ]);

    }
}
