<?php

namespace App\Http\Controllers\Mails;

use App\Http\Requests\Mail\ContactUsRequest;
use App\Http\Requests\Mail\VideocastSuggestionRequest;
use App\Mail\ContactMail;
use App\Mail\ConticastSuggestionMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Contact us
     * @param ContactUsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendContactUsEmail(ContactUsRequest $request)
    {
        activity('Email')->causedBy(request()->user())->log('E-mail de Fale Conosco: '. request()->ip() .'-'. now());

        try {
            Mail::to(config('mail.to'))->send(new ContactMail($request->validated()));
        } catch (\Exception $exception) {
            abort(404, 'Não foi possível enviar o e-mail. Tente novamente mais tarde');
        }

        return response()->json('Mensagem enviada com sucesso! Se possível, entraremos em contato com você.');

    }

    /**
     * ContiCast suggestion
     * @param VideocastSuggestionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVideocastSuggestionEmail(VideocastSuggestionRequest $request)
    {
        activity('Email')->causedBy(request()->user())->log('E-mail de sugestão Conti Cast: '. request()->ip() .' - '. now());

        try {
            Mail::to(config('mail.to'))->send(new ConticastSuggestionMail($request->validated()));
        } catch (\Exception $exception) {
            abort(404, 'Não foi possível enviar o e-mail. Tente novamente mais tarde');
        }

        return response()->json('Mensagem enviada com sucesso!');
    }
}
