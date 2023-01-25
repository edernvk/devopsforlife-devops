<?php

namespace App\Repositories\Interfaces;

interface AbstractInterface {
    public function findOrfail($id);

    public function paginate();

    public function with($id, $string);

    public function all();

    public function count();

    public function createdFrom($days);
}
