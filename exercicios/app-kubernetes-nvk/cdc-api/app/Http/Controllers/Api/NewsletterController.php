<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\NewsletterInterface;
use App\Traits\UploadTrait;

use App\Http\Requests\NewsletterStoreRequest;
use App\Http\Requests\NewsletterUpdateRequest;
use App\Http\Requests\UploadCoverRequest;
use App\Http\Resources\Newsletter;
use App\Http\Resources\NewsletterCollection;

class NewsletterController extends Controller
{
    use UploadTrait;

    protected $repository;

    public function __construct(NewsletterInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\NewsletterCollection
     */
    public function index() {
        activity('Newsletter')->causedBy(request()->user())->log('Listagem de newsletter (paginada)');

        return new NewsletterCollection($this->repository->paginate());
    }

    /**
     * List of newsletters
     *
     * Get a list of all newsletters
     *
     * @return \App\Http\Resources\NewsletterCollection
     */
    public function all() {
        activity('Newsletter')->causedBy(request()->user())->log('Listagem de newsletter (completa)');

        return new NewsletterCollection($this->repository->all());
    }

    public function recent() {
        activity('Newsletter')->causedBy(request()->user())->log('Listagem de newsletter (recentes)');

        return new NewsletterCollection($this->repository->recent());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NewsletterStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NewsletterStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $newsletter = $this->repository->create($request);

        activity('Newsletter')->causedBy(request()->user())->log('Newsletter salva: '.$newsletter->name);

        return response()->json(new Newsletter($newsletter), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $newsletter = $this->repository->findOrfail($id);

        activity('Newsletter')->causedBy(request()->user())->log('Newsletter consultada: '.$newsletter->name);

        return response()->json(new Newsletter($newsletter));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\NewsletterUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(NewsletterUpdateRequest $request, $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $newsletter = $this->repository->findOrfail($id);
        $newsletter = $this->repository->update($request, $newsletter);

        activity('Newsletter')->causedBy(request()->user())->log('Newsletter alterada: '.$newsletter->name);

        return response()->json(new Newsletter($newsletter));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $newsletter = $this->repository->findOrfail($id);
        $this->repository->delete($newsletter);

        activity('Newsletter')->causedBy(request()->user())->log('Newsletter deletada: '.$newsletter->name);

        return response()->json(null, 204);
    }

    /**
     * Store Magazine's Cover
     *
     * This endpoint store magazine's cover to the public/magazines-covers folder in server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam cover File Image uploaded.
     *
     * @authenticated
     * @response {
     *  "url": "http://api.casadiconti.com.br/storage/newsletters-covers/newsletter-ed150.png",
     *  "path": "newsletters-covers/newsletter-ed150.png"
     * }
     * @response 400 {}
     * @return \Illuminate\Http\JsonResponse
     */
    public function cover(UploadCoverRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $cover = $this->uploadOne($request->file('cover'), 'newsletters-covers', 's3', null);

        if ($cover) {
            return response()->json([
                'url' => Storage::url($cover),
                'path' => $cover
            ]);
        }

        return response()->json(null, 400);
    }
}
