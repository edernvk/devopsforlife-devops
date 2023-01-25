<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UploadPosterRequest;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use anlutro\LaravelSettings\Facade as Settings;
use Illuminate\Support\Facades\Storage;

class SimplifiedBenefitController extends Controller
{
    use UploadTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function set(Request $request)
    {
        Settings::set('simplified_benefits.message', $request->input('message'));
        Settings::set('simplified_benefits.poster', $request->input('poster'));
        Settings::set('simplified_benefits.updated_at', Carbon::now()->toDateTimeString());
        Settings::save();

        return response()->json([
            'message' => Settings::get('simplified_benefits.message'),
            'poster' => Settings::get('simplified_benefits.poster'),
            'updated_at' => Settings::get('simplified_benefits.updated_at'),
            'updated_at_forHumans' => Carbon::createFromTimeStamp(strtotime(Settings::get('simplified_benefits.updated_at')))->diffForHumans(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        return response()->json([
            'message' => Settings::get('simplified_benefits.message'),
            'poster' => Settings::get('simplified_benefits.poster'),
            'updated_at' => Settings::get('simplified_benefits.updated_at'),
            'updated_at_forHumans' => Carbon::createFromTimeStamp(strtotime(Settings::get('simplified_benefits.updated_at')))->diffForHumans(),
        ]);
    }

    public function disable()
    {
        //
    }

    /**
     * Store Benefit's Poster
     *
     * This endpoint store benefit's poster to the public/benefits-posters folder in server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam poster File Image uploaded.
     *
     * @authenticated
     * @response {
     *  "url": "http://api.casadiconti.com.br/storage/benefits-posters/random_string.png",
     *  "path": "benefits-posters/random_string.png"
     * }
     * @response 400 {}
     * @return \Illuminate\Http\JsonResponse
     */
    public function poster(UploadPosterRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $poster = $this->uploadOne($request->file('poster'), 'benefits-posters', 's3', null);

        if ($poster) {
            return response()->json([
                'url' => Storage::url($poster),
                'path' => $poster
            ]);
        }

        return response()->json(null, 400);
    }
}
