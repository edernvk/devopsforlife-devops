<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\StatusMessageInterface;
use App\Http\Requests\StatusMessageStoreRequest;
use App\Http\Resources\StatusMessage;
use App\Http\Requests\StatusMessageUpdateRequest;

class StatusMessageController extends Controller
{
    protected $repository;

    public function __construct(StatusMessageInterface $repository) {
        $this->repository = $repository;
    }

    public function index() { }

    public function show($id) { }

    public function store(StatusMessageStoreRequest $request) { }

    public function update(StatusMessageUpdateRequest $request, int $id) { }

    public function destroy($id) { }
}
