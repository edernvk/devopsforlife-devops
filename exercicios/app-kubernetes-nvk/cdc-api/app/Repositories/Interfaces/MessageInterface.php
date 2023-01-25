<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use Illuminate\Http\Request;

interface MessageInterface {
    public function create(MessageStoreRequest $request);

    public function createMessageByGroup(MessageStoreRequest $request);

    public function update(MessageUpdateRequest $request, $message);

    public function delete($message);

    public function read($model, $authUser, $read = true);

    public function getMessagesfromUser(Request $request, int $id);

    public function getUnreadMessagesfromUser(Request $request, int $id);

    public function getReadMessagesfromUser(Request $request, int $id);

    public function getOutbox(Request $request, int $id);

    public function getUserWhoRead(int $id);

    public function countUnreadInbox();

    public function publish($model);

    public function inactive($model);
}
