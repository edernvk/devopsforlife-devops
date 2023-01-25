<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Repositories\Interfaces\CommentInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;



class CommentController extends Controller
{
    protected $repository;

    public function __construct(CommentInterface $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:api');
    }

    public function all()
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return CommentResource::collection($this->repository->all());
    }


    public function index()
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return CommentResource::collection($this->repository->paginate(5));
    }

    public function store(CommentStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $comment = $this->repository->create($request);
        activity('Comment')->causedBy(request()->user())->log('Comentário salvo' . $comment->id);

        return response()->json($comment->load('commentUser'), 201);
    }

    public function show($id)
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $comment = $this->repository->findOrfail($id);
        return response()->json(new CommentResource($comment));
    }

    public function update(CommentUpdateRequest $request, int $id)
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $comment = $this->repository->findOrfail($id);
        $comment = $this->repository->update($request, $comment);
        activity('Comment')->causedBy(request()->user())->log('Comentário alterado' . $comment->id);
        return response()->json(new CommentResource($comment));
    }

    public function destroy($id)
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $comment = $this->repository->findOrfail($id);
        $this->repository->delete($comment);
        activity('Comment')->causedBy(request()->user())->log('Comentário excluído' . $comment->id);
        return response()->json(null, 204);
    }

    public function showCommentNews($id)
    {

        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $comment = Comment::where('newsletter_news_id', $id)->orderBy('created_at', 'desc')->get();

        $comment = $comment->load('commentUser');

        return response()->json($comment);
    }

    public function updateStatus($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $comment = $this->repository->findOrfail($id);


        $comment->update([
            'status' => !$comment->status
        ]);
        return response()->json(new CommentResource($comment));
    }

    public function deleteCommentsInNewsletter(Request $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        try {
            if (count($request->comments) > 0) {
                $comments = Comment::whereIn('id', $request->comments)->get();
                foreach ($comments as $comment) {
                    $comment->delete();
                }
                return response()->json(['comments' => $request->comments]);
            } else {
                return response()->json('Selecione comentários');
            }
        } catch (Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    public function statusCommentsMark(Request $request)
    {

        request()->user()->authorizeRoles(['Administrador']);


        $comments = Comment::whereIn('id', $request->comments)->get();

        if ($comments->contains('status', true)) {
            throw new Exception('apenas comentários não postados');
        }

        try {
            foreach ($comments as $comment) {
                $comment->update([
                    'status' => !$comment->status
                ]);
            }
            return response()->json(['comments' => CommentResource::collection($comments)]);
        } catch (Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }
}
