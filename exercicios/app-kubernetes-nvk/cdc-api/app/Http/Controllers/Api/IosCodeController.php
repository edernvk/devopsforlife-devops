<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IosCode as IosCodeResource;
use App\IosCode;
use Illuminate\Http\Request;

class IosCodeController extends Controller
{
    public function getCodeActive()
    {
        request()->user()->authorizeRoles(['Administrador']);

        $codes = IosCode::where('is_active', true)->paginate(10);

        activity('IosCode')->causedBy(request()->user())->log('Lista de codigos');

        return IosCodeResource::collection($codes);
    }

    public function getCodeInactive()
    {
        request()->user()->authorizeRoles(['Administrador']);

        $codes = IosCode::where('is_active', true)->paginate(10);

        activity('IosCode')->causedBy(request()->user())->log('Lista de codigos');

        return IosCodeResource::collection($codes);
    }
}
