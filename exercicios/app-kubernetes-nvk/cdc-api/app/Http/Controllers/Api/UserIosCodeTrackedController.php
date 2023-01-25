<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserIosCodeTrackedStoreRequest;
use App\IosCode;
use App\Mail\IosQrCodeMail;
use App\UserIosCodeTracked;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserIosCodeTrackedController extends Controller
{
    /**
     * Esse metodo esta fazendo muita coisa, refatorar
     */
    public function useCodeIos(UserIosCodeTrackedStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);
        
        try {
            $usedCode = DB::transaction(function () use ($request) {
                $code = IosCode::find($request->ioscode_id);
                
                $code->update([
                    'is_active' => false
                ]);
    
                Mail::to($request->email)->send(
                    new IosQrCodeMail($code)
                );
    
                return UserIosCodeTracked::create([
                    'ioscode_id' => $code->id,
                    'user_id' => Auth::id()
                ]);
            });

            activity('UserCodeTracked')->causedBy(request()->user())->log('Enviando codigo qr para email do colaborador');
            
            return response()->json($usedCode, 201);
        } catch (Exception $e) {
            abort(400, 'Nao foi possivel ');
        }
    }
}
