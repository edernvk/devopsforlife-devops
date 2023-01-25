<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Traits\UploadTrait;
use App\Http\Requests\UploadCoverRequest;
use App\Repositories\Interfaces\MagazineInterface;
use App\Http\Requests\MagazineStoreRequest;
use App\Http\Requests\MagazineUpdateRequest;
use App\Http\Resources\Magazine;
use App\Http\Resources\MagazineCollection;

class MagazineController extends Controller
{
    use UploadTrait;

    protected $repository;

    public function __construct(MagazineInterface $repository) {
        $this->repository = $repository;
        $this->middleware('auth:api');
    }

    /**
     * List Paginated Magazines
     *
     * Get a list of paginated magazines
     *
     * @authenticated
     * @responseFile 200 responses/magazines.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \App\Http\Resources\MagazineCollection
     */
    public function index() {
        activity('Magazine')->causedBy(request()->user())->log('Listagem de revistas (paginada)');

        return new MagazineCollection($this->repository->paginate());
    }

    /**
     * List of Magazines
     *
     * Get a list of all magazines
     *
     * @responseFile 200 responses/magazines.all.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \App\Http\Resources\MagazineCollection
     */
    public function all() {
        activity('Magazine')->causedBy(request()->user())->log('Listagem de revistas (completa)');

        return new MagazineCollection($this->repository->all());
    }

    public function recent() {
        activity('Magazine')->causedBy(request()->user())->log('Listagem de revistas (recentes)');

        return new MagazineCollection($this->repository->recent());
    }

    /**
     * Get Magazines
     *
     * Get magazine by it's unique ID.
     *
     * @pathParam id integer required The ID of the magazine to retrieve. Example: 1
     * @param  \App\Magazine  $id
     *
     * @authenticated
     * @responseFile 200 responses/magazines.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $magazine = $this->repository->findOrfail($id);

        activity('Magazine')->causedBy(request()->user())->log('Revista consultada: '.$magazine->title);

        return response()->json(new Magazine($magazine));
    }

    /**
     * Store Magazines
     *
     * Add a new magazine to the magazines collection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam title string required The name of the magazine. Example: Jornal Conti Tudo - Edição 67
     * @bodyParam link string required The link to online version of the magazine. Example: https://issuu.com/casadiconti/docs/jornal_conti_tudo_ed_67_virtual
     * @bodyParam cover string The string url for uploaded image from server response. Example: http://api.casadiconti.com.br/storage/jornalcontitudo-ed67.png
     *
     * @authenticated
     * @responseFile 201 responses/magazines.store.201.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/404.json
     * @responseFile 422 responses/magazines.store.422.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MagazineStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $maganize = $this->repository->create($request);

        activity('Magazine')->causedBy(request()->user())->log('Revista salva: '.$maganize->title);

        return response()->json(new Magazine($maganize), 201);
    }


    /**
     * Update Magazines
     *
     * Change information of a magazine in the magazines collection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam title string required The name of the magazine. Example: Jornal Conti Tudo - Edição 68
     * @bodyParam link string required The link to online version of the magazine. Example: https://issuu.com/casadiconti/docs/jornal_conti_tudo_ed_68_virtual
     * @bodyParam cover string The string url for uploaded image from server response. Example: http://api.casadiconti.com.br/storage/jornalcontitudo-ed68.png
     *
     * @pathParam id integer required The ID of the magazine to retrieve. Example: 1
     * @param  \App\Magazine  $id
     *
     * @authenticated
     * @responseFile 200 responses/magazines.update.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/magazines.update.404.json
     * @responseFile 422 responses/magazines.update.422.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MagazineUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $magazine = $this->repository->findOrfail($id);
        $magazine = $this->repository->update($request, $magazine);

        activity('Magazine')->causedBy(request()->user())->log('Revista alterada: '.$magazine->title);

        return response()->json(new Magazine($magazine));
    }

    /**
     * Delete Magazines
     *
     * Delete a magazine from the magazines collection.
     *
     * @pathParam id integer required The ID of the magazine to retrieve. Example: 1
     * @param  \App\Magazine  $id
     *
     * @authenticated
     * @response 204 {}
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/magazines.delete.404.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $magazine = $this->repository->findOrfail($id);
        $this->repository->delete($magazine);

        activity('Magazine')->causedBy(request()->user())->log('Revista deletada: '.$magazine->title);

        return response()->json(null, 204);
    }


    /**
     * Store Magazine's Cover
     *
     * This endpoint store magazine's cover to the public/magazines-covers folder in server.
     *
     * @param  \App\Http\Requests\UploadCoverRequest  $request
     * @bodyParam cover File Image uploaded.
     *
     * @authenticated
     * @response {
     *  "url": "http://api.casadiconti.com.br/storage/magazines-covers/jornalcontitudo-ed67.png",
     *  "filename": "jornalcontitudo-ed67.png"
     * }
     * @response 400 {}
     * @return \Illuminate\Http\JsonResponse
     */
    public function cover(UploadCoverRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $cover = $this->uploadOne($request->file('cover'), 'magazines-covers', 's3', null);

        if ($cover) {
            return response()->json([
                'url' => Storage::url($cover),
                'path' => $cover
            ]);
        }

        return response()->json(null, 400);
    }
}
