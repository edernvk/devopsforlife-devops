<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StatusMessageStoreRequest;
use App\Http\Requests\StatusMessageUpdateRequest;

interface StatusMessageInterface {
    public function create(StatusMessageStoreRequest $request);

    public function update(StatusMessageUpdateRequest $request, $statusmessage);

    public function delete($statusmessage);
}
