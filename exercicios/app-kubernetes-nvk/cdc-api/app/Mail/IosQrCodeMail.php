<?php

namespace App\Mail;

use App\IosCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IosQrCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public IosCode $iosCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(IosCode $iosCode)
    {
        $this->iosCode = $iosCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[CDC Digital] - Codigo Ios')
        ->markdown('emails.ios-qr-code');
    }
}
