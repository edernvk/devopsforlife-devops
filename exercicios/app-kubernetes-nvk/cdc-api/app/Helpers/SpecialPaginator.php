<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class SpecialPaginator extends LengthAwarePaginator {

    /**
     * Create a new paginator instance.
     *
     * @param  mixed  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int|null  $currentPage
     * @param  array  $options (path, query, fragment, pageName)
     * @return void
     */
    public function __construct($items, $total, $perPage, $currentPage = null, array $options = [])
    {
        parent::__construct($items, $total, $perPage, $currentPage, $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => $this->items->values(),
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'next' => $this->nextPageUrl(),
                'prev' => $this->previousPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'path' => $this->path,
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
            ]
        ];
    }
}
