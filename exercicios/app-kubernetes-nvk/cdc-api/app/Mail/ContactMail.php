<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @param $data array
     */
    public function __construct($data)
    {
        // to convert array to object
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
        //Adicionar caso for necessário ter bcc
        // ->bcc(Str::contains($currentUser->email, '@penze.com.br')
        //     ? ['cristiano@penze.com.br', 'tecnologia@penze.com.br']
        //     : [])
        return $this
            ->from($currentUser->email, $currentUser->name)
            ->replyTo($currentUser->email, $currentUser->name)
            ->subject('[CDC Digital] Fale conosco: '.$this->data->subject)
            ->markdown('emails.contact-us')
            ->with([
                'name' => $currentUser->name,
                'email' => $currentUser->email,
                'mobile' => $currentUser->mobile,
                'ip' => request()->ip(),
                'subject' => $this->data->subject,
                'message' => $this->data->message
            ]);
    }
}
