<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Message;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserSimplified;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MessageCollection;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Repositories\Eloquent\MessageEloquent;
use App\Repositories\Interfaces\MessageInterface;
use App\Http\Resources\OptimizedMessageCollection;
use App\Http\Resources\MessageMinimalistCollection;

/**
 * @group Message
 */
class MessageController extends Controller
{
    protected $repository;

    public function __construct(MessageInterface $repository) {
        $this->repository = $repository;
        $this->middleware('register-last-login')->only(['show']);
    }

    /**
     * List Paginated Messages
     *
     * Get a list of paginated messages
     *
     * @authenticated
     * @responseFile 200 responses/messages.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function index() {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return new MessageCollection($this->repository->paginate());
    }

    /**
     * Inbox
     *
     * Get a list of paginated user's messages in inbox
     *
     * @authenticated
     * @responseFile 200 responses/messages.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function inbox(Request $request, int $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return new MessageCollection($this->repository->getMessagesfromUser($request, $id));
    }

    /**
     * Get Formatted Inbox
     *
     * @param integer $id user id
     * @return Collection
     */
    public function inboxFormatted(Request $request, $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);
        activity('Message')->causedBy(request()->user())->log('Inbox consultada (paginada). Usuário: '.$id);

        return MessageMinimalistCollection::collection($this->repository->getMessagesfromUser($request, $id));
        // return OptimizedMessageCollection::collection($this->repository->getMessagesfromUser($id));
//        return $this->repository->getMessagesfromUser($id);
    }

    /**
     * Get Formatted Inbox (Unread)
     *
     * @param integer $id user id
     * @return Collection
     */
    public function inboxFormattedUnread(Request $request, $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);
        activity('Message')->causedBy(request()->user())->log('Inbox consultada (não lidas). Usuário: '.$id);

        return MessageMinimalistCollection::collection($this->repository->getUnreadMessagesfromUser($request, $id));
//      return $this->repository->getUnreadMessagesfromUser($id);
    }

    /**
     * Get Formatted Inbox (Read)
     *
     * @param integer $id user id
     * @return Collection
     */
    public function inboxFormattedRead(Request $request, $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);
        activity('Message')->causedBy(request()->user())->log('Inbox consultada (lidas). Usuário: '.$id);

        return MessageMinimalistCollection::collection($this->repository->getReadMessagesfromUser($request, $id));
//        return $this->repository->getReadMessagesfromUser($id);
    }

    /**
     * Inbox Count Unread
     *
     * Get how many messages the user haven't read yet
     *
     * @authenticated
     * @responseFile 200 {"count": 200}
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function inboxCount() {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return response()->json(['count' => $this->repository->countUnreadInbox()]);
    }

    /**
     * Outbox
     *
     * Get a list of paginated user's messages in outbox
     *
     * @authenticated
     * @responseFile 200 responses/messages.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function outbox(Request $request, $id) {
        request()->user()->authorizeRoles(['Administrador']);

        return MessageMinimalistCollection::collection($this->repository->getOutbox($request, $id));
    }

    /**
     * User who read
     *
     * Get a list of paginated user's who read the message
     *
     * @authenticated
     * @responseFile 200 responses/users.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function reads($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $readers = $this->repository->getUserWhoRead($id);

        return response([
            "total" => $readers->count(),
            "data" => UserSimplified::collection($readers)
        ]);
    }

    /**
     * Get Messages
     *
     * Get message by it's unique ID.
     *
     * @pathParam id integer required The ID of the message to retrieve. Example: 1
     * @param  \App\Message  $id
     *
     * @authenticated
     * @responseFile 200 responses/messages.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $message = $this->repository->findOrfail($id);
        $message->loadMissing(['to', 'fromUser']);

        activity('Message')->causedBy(request()->user())->log('Mensagem aberta: '.$message->title);

        return response()->json(new Message($message));
    }

    /**
     * Store Messages
     *
     * Add a new message to the messages collection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam from integer required The id of the user who send the message. Example: 1
     * @bodyParam title string required The message's title. Example: See what are the news
     * @bodyParam description text The message body. Example: Loren ipsum is nice.
     * @bodyParam to array required Array of users' id destinations. Example: [1, 2]
     *
     * @authenticated
     * @responseFile 201 responses/messages.store.201.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/404.json
     * @responseFile 422 responses/messages.store.422.json
     * @return \Illuminate\Http\Response
     */
    public function store(MessageStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $message = $this->repository->create($request);

        activity('Message')->causedBy(request()->user())->log('Mensagem salva: '.$message->title);

        return response()->json(new Message($message), 201);
    }

    public function storeMessageByGroup(MessageStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $message = $this->repository->createMessageByGroup($request);

        activity('Message')->causedBy(request()->user())->log('Mensagem salva: '.$message->title);

        return response()->json(new Message($message), 201);
    }

    /**
     * Update Messages
     *
     * Change information of a message in the messages collection.
     *
     * @pathParam id integer required The ID of the message to retrieve. Example: 1
     * @param  \App\Message  $id
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam from integer required The id of the user who send the message. Example: 1
     * @bodyParam title string required The message's title. Example: See what are the news Updated
     * @bodyParam description text The message body. Example: Loren ipsum is nice and Updated.
     * @bodyParam to array required Array of users' id destinations. Example: [1]
     *
     * @authenticated
     * @responseFile 200 responses/messages.update.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/messages.update.404.json
     * @responseFile 422 responses/messages.update.422.json
     * @return \Illuminate\Http\Response
     */
    public function update(MessageUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $message = $this->repository->findOrfail($id);
        $message = $this->repository->update($request, $message);

        activity('Message')->causedBy(request()->user())->log('Mensagem alterada: '.$message->title);

        return response()->json(new Message($message));
    }

    /**
     * Delete Messages
     *
     * Delete a message from the messages collection.
     *
     * @pathParam id integer required The ID of the message to retrieve. Example: 1
     * @param  \App\Message  $id
     *
     * @authenticated
     * @response 204 {}
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/messages.delete.404.json
     */
    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $message = $this->repository->findOrfail($id);
        $this->repository->delete($message);

        activity('Message')->causedBy(request()->user())->log('Mensagem excluída: '.$message->title);

        return response()->json(null, 204);
    }

    /**
     * Store Message Images
     *
     * This endpoint store images from the body of the message to the public/messages-images folder in server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam image File Image uploaded.
     *
     * @authenticated
     * @response {
     *  "location": "public/messages-images/123123123-fileimage-test.jpg"
     * }
     * @response 500 {}
     */
    public function imageStorage(Request $request) {
        if($request->hasFile('image')) {
            $filePath = Storage::disk('s3')->putFileAs(
                'messages-images/'.time().rand(1,100).$request->file('image')->getClientOriginalName(),
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

    /**
     * Read Messages
     *
     * Read a message from the messages collection.
     *
     * @pathParam id integer required The ID of the message to retrieve. Example: 1
     * @param  \App\Message  $id
     *
     * @authenticated
     * @response 200 true
     * @response 500 false
     * @responseFile 401 responses/401.json
     */
    public function read($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $message = $this->repository->findOrfail($id);
        if($this->repository->read($message, auth()->user())) {
            return response()->json(true, 200);
        } else {
            return response()->json(false, 500);
        }
    }

    /**
     * Unread Messages
     *
     * Unread a message from the messages collection.
     *
     * @pathParam id integer required The ID of the message to retrieve. Example: 1
     * @param  \App\Message  $id
     *
     * @authenticated
     * @response 200 true
     * @response 500 false
     * @responseFile 401 responses/401.json
     */
    public function unread($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $message = $this->repository->findOrfail($id);
        if($this->repository->read($message, auth()->user(), false)) {
            return response()->json(true, 200);
        } else {
            return response()->json(false, 500);
        }
    }

    public function publish($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $message = $this->repository->findOrfail($id);

        if($this->repository->publish($message)) {
            activity('Message')->causedBy(request()->user())->log('Mensagem Publicada: '.$message->title);

            return response()->json(true, 200);
        } else {
            return response()->json(false, 500);
        }
    }

    public function inactive($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $message = $this->repository->findOrfail($id);

        if($this->repository->inactive($message)) {
            activity('Message')->causedBy(request()->user())->log('Mensagem Desativada: '.$message->title);

            return response()->json(true, 200);
        } else {
            return response()->json(false, 500);
        }
    }
}
