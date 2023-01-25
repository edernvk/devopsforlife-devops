<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HealthDocsStoreRequest;
use App\Http\Resources\HealthDocs;
use App\Http\Resources\HealthDocsCollection;
use App\Repositories\Interfaces\HealthDocsInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Str;

class HealthDocsController extends Controller
{
    protected $repository;
    protected $userRep;

    public function __construct(
        HealthDocsInterface $repository,
        UserInterface $userRep
    ) {
        $this->repository = $repository;
        $this->userRep = $userRep;
        $this->middleware('auth:api');
    }

    /**
     * List all HealthDocs
     *
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function index() {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return new HealthDocsCollection($this->repository->paginate());
    }

    /**
     * List all HealthDocs
     *
     * @param integer $id user_id
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function allByUser($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return new HealthDocsCollection($this->repository->getFromUser($id));
    }

    /**
     * Save a new healthDocs
     *
     * @param \App\Repositories\Interfaces\HealthDocsInterface $request
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function store(HealthDocsStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $doc = $this->repository->create($request);

        return response()->json(new HealthDocs($doc), 201);
    }

    /**
     * Delete a HealthDocs
     *
     * @param integer $id
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $doc = $this->repository->findOrfail($id);
        $this->repository->delete($doc);

        return response()->json(null, 204);
    }

    /**
     * Store a pdf doc file
     *
     * @param \Illuminate\Http\Request $request
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function storePdfDoc(Request $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $pdf = $this->repository->storePdf($request);
        if($pdf) {
            return response()->json(['location' => $pdf]);
        } else {
            return response()->json(null, 400);
        }
    }

    /**
     * Load a full package of pdf doc file
     *
     * @param \Illuminate\Http\Request $request
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function storeLoadPdfDoc(Request $request) {
        request()->user()->authorizeRoles(['Administrador']);

        if(!$request->hasFile('pdf')) return response()->json(null, 400);

        $pdfName = $request->file('pdf')->getClientOriginalName();
        $onlyCpf = Str::substr($pdfName, 0, 11);
        $title = $request->title;

        $pdfPath = $this->repository->storePdf($request);

        $user = $this->userRep->getByCpf($onlyCpf);

        if(!$user) return response()->json(null, 400);

        $doc = $this->repository->createFromArray([
            'user_id' => $user->id,
            'url_doc' => $pdfPath,
            'title' => $title
        ]);

        if(!$doc) return response()->json(null, 400);

        return response()->json($user->id, 201);
    }
}
