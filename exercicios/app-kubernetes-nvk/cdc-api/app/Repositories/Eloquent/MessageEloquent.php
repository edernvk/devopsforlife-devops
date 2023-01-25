<?php

namespace App\Repositories\Eloquent;

use App\Helpers\Collection;
use App\Repositories\Interfaces\MessageInterface;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Message;
use App\StatusMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageEloquent extends AbstractEloquent implements MessageInterface {

    public function __construct() {
        parent::__construct('Message');
    }

    public function create(MessageStoreRequest $request) {
        $formattedRequest = $request->except('to');
        $formattedRequest['publish_datetime'] = Carbon::parse($formattedRequest['publish_datetime'], 'America/Sao_Paulo')->toDateTimeString();

        $message = Message::create($formattedRequest);

        $to = collect($request->to);
        if($to->isNotEmpty()) {
            $users = User::whereIn('cpf', $to)->get();
            $message->to()->attach($users->pluck('id'));
        }

        return $message;
    }

    public function createMessageByGroup(MessageStoreRequest $request) {
        $formattedRequest = $request->except('to');
        $formattedRequest['publish_datetime'] = Carbon::parse($formattedRequest['publish_datetime'], 'America/Sao_Paulo')->toDateTimeString();

        $message = Message::create($formattedRequest);

        $to = collect($request->to);
        if($to->isNotEmpty())
        $message->to()->attach($to);

        return $message;
    }

    public function getMessagesfromUser(Request $request, int $userId) {

//        $messages = collect(DB::select(DB::raw("
//            SELECT
//                `messages`.`id`, `messages`.`title`, `messages`.`description`, `messages`.`from`,
//                `messages`.`created_at`, `messages`.`status_id`, `messages`.`publish_datetime`,
//                `users`.`name`, `users`.`avatar`
//            FROM `messages`
//            INNER JOIN `users` ON `messages`.`from` = `users`.`id`
//            WHERE `from` <> {$id}
//                AND `publish_datetime` IS NOT NULL
//                AND `publish_datetime` < NOW()
//                AND `status_id` = 3
//                AND exists (
//                    SELECT * FROM `users`
//                    INNER JOIN `messages_users` ON `users`.`id` = `messages_users`.`user_id`
//                    WHERE `messages`.`id` = `messages_users`.`message_id`
//                    AND `user_id` = {$id}
//                )
//            ORDER BY `created_at` DESC
//        ")));
//
//        $ids = $messages->implode('id', ', ');
//
//        $messageStatus = collect(DB::select(DB::raw("
//            SELECT
//                `messages_users`.`message_id`,
//                `messages_users`.`read`
//            FROM `users`
//            INNER JOIN `messages_users` ON `users`.`id` = `messages_users`.`user_id`
//            WHERE `messages_users`.`message_id` IN (${ids})
//            AND `users`.`id` = {$id}
//        ")));
//
//        $messages = Collection::paginate($messages, $messages->count(), 5);
//
//        $messages->map(function ($item) use ($messageStatus) {
//            $item->forHumans = Carbon::createFromTimeStamp(strtotime($item->created_at))->diffForHumans();
//
//            $item->current_user_read = $messageStatus->filter(function($value) use ($item) {
//                return $item->id ===  $value->message_id;
//            })->implode('read');
//
//            return $item;
//        });

        DB::table('messages')->where('status_id', '4')->chunkById(100, function ($announcements) {
            $currentDate = now()->format('Y-m-d H:i:s');
            foreach ($announcements as $message) {
                if ($message->publish_datetime <= $currentDate) {
                    DB::table('messages')->where('id', $message->id)->update(['status_id' => '3']);
                }
            }
        });

        $messages = Message::select([
                'id',
                'title',
                'description',
                'from',
                'created_at',
                'status_id',
                'publish_datetime'
            ])
            ->with('fromUser')
            ->withCount(['usersRead as current_user_read' => function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            }])
            ->whereHas('to', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereNotNull('publish_datetime')
            ->where('from', '!=', $userId)
            ->where('status_id', StatusMessage::PUBLISHED)
            ->orderBy('publish_datetime', 'DESC');

        if ($request->has('search')) {
            $messages->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('description', 'like', '%' . $request->query('search') . '%');
            });
        }

        // this can be replaced with `$messages->paginate(5)->withQueryString()` on Laravel 7
        return $messages->paginate(5)->appends(request()->except('page'));
    }

    public function getUnreadMessagesfromUser(Request $request, int $userId) {

//        $messages = collect(DB::select(DB::raw("
//            SELECT
//                `messages`.`id`, `messages`.`title`, `messages`.`description`, `messages`.`from`,
//                `messages`.`created_at`, `messages`.`status_id`, `messages`.`publish_datetime`,
//                `users`.`name`, `users`.`avatar`
//            FROM `messages`
//            INNER JOIN `users` ON `messages`.`from` = `users`.`id`
//            WHERE `from` <> {$userId}
//                AND `publish_datetime` IS NOT NULL
//                AND `publish_datetime` < NOW()
//                AND `status_id` = 3
//                AND exists (
//                    SELECT * FROM `users`
//                    INNER JOIN `messages_users` ON `users`.`id` = `messages_users`.`user_id`
//                    WHERE `messages`.`id` = `messages_users`.`message_id`
//                    AND `messages_users`.`read` IS NULL
//                    AND `user_id` = {$userId}
//                )
//            ORDER BY `created_at` DESC
//        ")));

//        $ids = $messages->implode('id', ', ');
//
//        $messageStatus = collect(DB::select(DB::raw("
//            SELECT
//                `messages_users`.`message_id`,
//                `messages_users`.`read`
//            FROM `users`
//            INNER JOIN `messages_users` ON `users`.`id` = `messages_users`.`user_id`
//            WHERE `messages_users`.`message_id` IN ({$ids})
//            AND `users`.`id` = {$userId}
//        ")));
//
//        $messages = Collection::paginate($messages, $messages->count(), 5);
//
//        $messages->map(function ($item) use ($messageStatus) {
//            $item->forHumans = Carbon::createFromTimeStamp(strtotime($item->created_at))->diffForHumans();
//
//            $item->current_user_read = $messageStatus->filter(function($value) use ($item) {
//                return $item->id ===  $value->message_id;
//            })->implode('read');
//
//            return $item;
//        });

        DB::table('messages')->where('status_id', '4')->chunkById(100, function ($announcements) {
            $currentDate = now()->format('Y-m-d H:i:s');
            foreach ($announcements as $message) {
                if ($message->publish_datetime <= $currentDate) {
                    DB::table('messages')->where('id', $message->id)->update(['status_id' => '3']);
                }
            }
        });

        $messages = Message::select([
                'id',
                'title',
                'description',
                'from',
                'created_at',
                'status_id',
                'publish_datetime'
            ])
            ->with('fromUser')
            ->withCount(['usersRead as current_user_read' => function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            }])
            ->whereHas('usersNotRead', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereNotNull('publish_datetime')
            ->where('from', '!=', $userId)
            ->where('status_id', StatusMessage::PUBLISHED)
            ->orderBy('publish_datetime', 'DESC');

        if ($request->has('search')) {
            $messages->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('description', 'like', '%' . $request->query('search') . '%');
            });
        }

        // this can be replaced with `$messages->paginate(5)->withQueryString()` on Laravel 7
        return $messages->paginate(5)->appends(request()->except('page'));
    }

    public function getReadMessagesfromUser(Request $request, int $userId) {

//        $messages = collect(DB::select(DB::raw("
//            SELECT
//                `messages`.`id`, `messages`.`title`, `messages`.`description`, `messages`.`from`,
//                `messages`.`created_at`, `messages`.`status_id`, `messages`.`publish_datetime`,
//                `users`.`name`, `users`.`avatar`
//            FROM `messages`
//            INNER JOIN `users` ON `messages`.`from` = `users`.`id`
//            WHERE `from` <> {$id}
//                AND `publish_datetime` IS NOT NULL
//                AND `publish_datetime` < NOW()
//                AND `status_id` = 3
//                AND exists (
//                    SELECT * FROM `users`
//                    INNER JOIN `messages_users` ON `users`.`id` = `messages_users`.`user_id`
//                    WHERE `messages`.`id` = `messages_users`.`message_id`
//                    AND `messages_users`.`read` IS NOT NULL
//                    AND `user_id` = {$id}
//                )
//            ORDER BY `created_at` DESC
//        ")));
//
//        $ids = $messages->implode('id', ', ');
//
//        $messageStatus = collect(DB::select(DB::raw("
//            SELECT
//                `messages_users`.`message_id`,
//                `messages_users`.`read`
//            FROM `users`
//            INNER JOIN `messages_users` ON `users`.`id` = `messages_users`.`user_id`
//            WHERE `messages_users`.`message_id` IN (${ids})
//            AND `users`.`id` = {$id}
//        ")));
//
//        $messages = Collection::paginate($messages, $messages->count(), 5);
//
//        $messages->map(function ($item) use ($messageStatus) {
//            $item->forHumans = Carbon::createFromTimeStamp(strtotime($item->created_at))->diffForHumans();
//
//            $item->current_user_read = $messageStatus->filter(function($value) use ($item) {
//                return $item->id ===  $value->message_id;
//            })->implode('read');
//
//            return $item;
//        });

        $messages = Message::select([
                'id',
                'title',
                'description',
                'from',
                'created_at',
                'status_id',
                'publish_datetime'
            ])
            ->with('fromUser')
            ->withCount(['usersRead as current_user_read' => function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            }])
            ->whereHas('usersRead', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereNotNull('publish_datetime')
            ->where('from', '!=', $userId)
            ->where('status_id', StatusMessage::PUBLISHED)
            ->orderBy('publish_datetime', 'DESC');

        if ($request->has('search')) {
            $messages->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('description', 'like', '%' . $request->query('search') . '%');
            });
        }

        // this can be replaced with `$messages->paginate(5)->withQueryString()` on Laravel 7
        return $messages->paginate(5)->appends(request()->except('page'));
    }

    public function getOutbox(Request $request, int $userId) {

        DB::table('messages')->where('status_id', '4')->chunkById(100, function ($announcements) {
            foreach ($announcements as $message) {
                $currentDate = now()->format('Y-m-d H:i:s');
                if ($message->publish_datetime <= $currentDate) {
                    DB::table('messages')->where('id', $message->id)->update(['status_id' => '3']);
                }
            }
        });

        $messages = Message::select([
                'id',
                'title',
                'description',
                'from',
                'publish_datetime',
                'status_id',
                'created_at'
            ])
            ->where('from', $userId)
            ->orderBy('created_at', 'desc')
            ->when($request->has('search'), function($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('title', 'like', '%' . $request->query('search') . '%')
                        ->orWhere('description', 'like', '%' . $request->query('search') . '%');
                });
            })
            ->simplePaginate(20)->appends(request()->except('page'));

        return $messages;
    }

    public function getUserWhoRead(int $messageId) {
//        $users =
//            DB::table('messages_users')
//                ->join('users', 'users.id', '=', 'messages_users.user_id')
//                ->join('messages', 'messages.id', '=', 'messages_users.message_id')
//                ->select('users.*', 'messages_users.read')
//                ->where('messages.id', $id)
//                ->where('messages_users.read', '<>', null)
//                ->get();

        $users = User::select([
            'id',
            'name',
            'cpf',
            'email',
            'registration',
            'avatar',
            'team_id',
            'approved'
        ])->whereHas('readMessages', function (Builder $query) use ($messageId) {
            $query->where('message_id', $messageId);
        })->get();

        return $users;
    }

    public function countUnreadInbox()
    {
        $messages =
            DB::table('messages_users')
                ->join('users', 'users.id', '=', 'messages_users.user_id')
                ->join('messages', 'messages.id', '=', 'messages_users.message_id')
                ->select('messages.id', 'messages_users.read')
                ->where('users.id', auth()->user()->id)
                ->where('messages_users.read', null)
                ->whereNotNull('messages.publish_datetime')
                ->where('messages.publish_datetime', '<', new \DateTime())
                ->where('messages.status_id', 3)
                ->get();

        return $messages->count();
    }

    public function update(MessageUpdateRequest $request, $model) {
        $formattedRequest = $request->except('to');
        $formattedRequest['publish_datetime'] = Carbon::parse($formattedRequest['publish_datetime'], 'America/Sao_Paulo')->toDateTimeString();

        $model->update($formattedRequest);

        $to = collect($request->to);
        if($to->isNotEmpty())
            $model->to()->sync($to);

        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function read($model, $authUser, $read = true) {
        if($read) {
            return $model->to()->updateExistingPivot($authUser->id, ['read' => Carbon::now()]);
        } else {
            return $model->to()->updateExistingPivot($authUser->id, ['read' => null]);
        }
    }

    public function publish($model) {
        $model->status_id = 3;
        $model->publish_datetime = Carbon::now();

        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function inactive($model) {
        $model->status_id = 1;

        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    private function handlePublished() {}
}
