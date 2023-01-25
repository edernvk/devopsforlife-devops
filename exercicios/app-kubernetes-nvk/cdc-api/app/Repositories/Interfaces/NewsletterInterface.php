<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\NewsletterStoreRequest;
use App\Http\Requests\NewsletterUpdateRequest;

interface NewsletterInterface {
    public function all();

    public function recent();

    public function create(NewsletterStoreRequest $request);

    public function update(NewsletterUpdateRequest $request, $newsletter);

    public function delete($newsletter);
}
