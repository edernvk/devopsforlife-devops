<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\NewsletterNewsInterface;
use App\Http\Requests\NewsletterNewsStoreRequest;
use App\Http\Resources\NewsletterNews;
use App\Http\Requests\NewsletterNewsUpdateRequest;
use App\Http\Resources\Newsletter;
use App\Http\Resources\NewsletterNewsCollection;
use App\Http\Resources\UserSimplified;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsletterNewsController extends Controller
{

    use UploadTrait;

    protected $repository;

    public function __construct(NewsletterNewsInterface $repository) {
        $this->repository = $repository;
    }

    public function all() {
        activity('NewsletterNews')->causedBy(request()->user())->log('Listagem da nova newsletter (completa)');

        return new NewsletterNewsCollection($this->repository->all());
    }

    public function index() {
        $newsletterNews = $this->repository->paginate();

        activity('NewsletterNews')->causedBy(request()->user())->log('Listagem da nova newsletter (paginada)');

        return new NewsletterNewsCollection($newsletterNews);
    }

    public function getPublisheds() {
        $newsletterNews = $this->repository->publisheds();

        activity('NewsletterNews')->causedBy(request()->user())->log('Listagem de newsletter publicadas');

        return new NewsletterNewsCollection($newsletterNews);
    }

    public function recent()
    {
        activity('NewsletterNews')->causedBy(request()->user())->log('Listagem da nova newsletter (recentes)');

        return new NewsletterNewsCollection($this->repository->recent());
    }

    public function show($id) {
        $newsletterNews = $this->repository->findOrfail($id);

        activity('NewsletterNews')->causedBy(request()->user())->log('Nova newletter consultada: ' .$newsletterNews->title);

        return new NewsletterNews($newsletterNews);
    }

    public function store(NewsletterNewsStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $newsletterNews = $this->repository->create($request);

        activity('NewsletterNews')->causedBy(request()->user())->log('Nova newletter salva: ' .$newsletterNews->title);

        return response()->json(new NewsletterNews($newsletterNews), 201);
    }

    public function update(NewsletterNewsUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);
        $newsletterNews = $this->repository->update($request, $model);

        activity('NewsletterNews')->causedBy(request()->user())->log('Nova newletter alterada ' .$newsletterNews->title);

        return new NewsletterNews($newsletterNews);
    }

    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);
        $this->repository->delete($model);

        activity('NewsletterNews')->causedBy(request()->user())->log('Nova newletter deletada ' . $model->title);

        return response()->json(null, 204);
    }

    public function changeNewsletterStatus($id)
    {
        $model = $this->repository->findOrfail($id);

        $newsletterNews = $this->repository->changeStatus($model);

        return response()->json($newsletterNews);
    }

    public function getIsActives()
    {
        $newslettersNews = $this->repository->isActives();

        return NewsletterNews::collection($newslettersNews);
    }

    public function getContrast()
    {
        $newsletterNews = $this->repository->findContrast();

        return new NewsletterNews($newsletterNews);
    }

    public function postLike(int $id)
    {
        $model = $this->repository->findOrfail($id);
        try {
            $like = $this->repository->like(
                $model,
                Auth::user()
            );
            activity('NewsletterNews')->causedBy(request()->user())->log('Newletter curtida ' . $model->title);

            return response()->json('foi', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 200);
        }
    }

    public function getUsersLike(int $id)
    {
        $model = $this->repository->findOrfail($id);

        $usersLike = $this->repository->countUsersLike($model);

        activity('NewsletterNews')->causedBy(request()->user())->log('Contagem de acesso da Newletter ' . $model->title);

        return $usersLike;
    }

    public function findByUserLike(int $id, int $idUser) {
        $response = $this->repository->findByUserRead($id, $idUser);

        if (empty($response)) {
            return response()->json([
                'liked' => false
            ]);
        } else {
            return response()->json([
                'liked' => true
            ]);
        }
    }

    public function deleteLike(int $id, int $idUser) {
        $this->repository->deleteLike($id, $idUser);

        activity('NewsletterNews')->causedBy(request()->user())->log('Newletter descurtida!');

        return response()->json(null, 204);
    }

    public function postRead(int $id)
    {
        $model = $this->repository->findOrfail($id);
        try {
            $read = $this->repository->read(
                $model,
                Auth::user()
            );
            activity('NewsletterNews')->causedBy(request()->user())->log('Newletter lida ' . $model->title);

            return response()->json('foi', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 200);
        }
    }

    public function getUsersRead(int $id)
    {
        $model = $this->repository->findOrfail($id);

        $usersRead = $this->repository->countUsersRead($model);

        activity('NewsletterNews')->causedBy(request()->user())->log('Contagem de acesso da Newletter ' . $model->title);

        return $usersRead;
    }

    public function getUsersUnRead(int $id)
    {
        $model = $this->repository->findOrfail($id);
        $read = $this->repository->countUsersRead($model);
        $usersRead = $this->repository->countUsersUnread($model, $read);

        activity('NewsletterNews')->causedBy(request()->user())->log('Contagem de não acesso da Newletter ' . $model->title);

        return $usersRead;
    }

    public function getTaxOpened(int $id)
    {
        abort(405);
        $model = $this->repository->findOrfail($id);
        $tax = $this->repository->taxOpened($model);

        return response()->json($tax);
    }
    /** aplicando conceito do BFF(Backend For Frontend) */
    public function getReportNewsletter(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);
        $model = $this->repository->findOrfail($id);

        $read = $this->repository->countUsersRead($model);
        $unread = $this->repository->countUsersUnread($model, $read);
        $percentage = $this->repository->percentageUsersRead($read, $unread);

        $model->load('user');

        return response()->json([
            'newsletter' => new NewsletterNews($model),
            'read' => $read,
            'unread' => $unread,
            'reading_percentage' => number_format($percentage, 2)
        ]);
    }

    public function getUserReadView(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);

        $usersRead = $this->repository->usersReadView($model);

        return response()->json(
            UserSimplified::collection($usersRead)
        );
    }

    public function getUserUnreadView(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);

        $usersUnread = $this->repository->usersUnreadView($model);

        return response()->json(
            UserSimplified::collection($usersUnread)
        );
    }

    public function postChechContrast(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);

        $newsletterNews = $this->repository->checkContrast($model);

        return response()->json($newsletterNews);
    }

    /**
     * Store Newsletter News Images
     *
     * This endpoint store images from the body of the message to the public/newsletter-news-images folder in server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam image File Image uploaded.
     *
     * @authenticated
     * @response {
     *  "location": "public/newsletter-news-images/123123123-fileimage-test.jpg"
     * }
     * @response 500 {}
     */
    public function imageStorage(Request $request) {
        if($request->hasFile('image')) {
            $filePath = Storage::disk('s3')->putFileAs(
                'newsletter-news-images/'.time().rand(1,100).$request->file('image')->getClientOriginalName(),
                $request->file('image') ,
                ''
            );
            $path = Storage::url($filePath);

            return response()->json(
                ['location' => $path]
            );
        } else {
            return response()->json(null, 500);
        }
    }

    public function thumbnail(Request $request) {
        request()->user()->authorizeRoles(['Administrador']);
        $thumbnail = $this->uploadOne($request->file('thumbnail'), 'newsletter-news-thumbnails', 's3', null);
        if ($thumbnail) {
            return response()->json([
                'url' => Storage::url($thumbnail),
                'path' => $thumbnail
            ]);
        }
        return response()->json(null, 400);
    }

    public function updateThumbnail(int $id, Request $request) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $request->replace($request->all());

        $user = $this->repository->findOrfail($id);

        $thumbnail = $this->uploadOne($request->file('thumbnail'), 'newsletter-news-thumbnails', 's3', null);
        $user->thumbnail = Storage::url($thumbnail);
        $user->save();

        if($thumbnail) {
            return response()->json([
                'url' => Storage::url($thumbnail),
                'path' => $thumbnail
            ]);
        }

        return response()->json(null, 400);
    }
}
