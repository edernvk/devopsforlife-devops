<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection as IlluminateCollection;

class Collection {

    /**
     * Paginate Helper
     * @param Illuminate\Support\Collection $results
     * @param int $total
     * @param int $pageSize
     */
    public static function paginate(IlluminateCollection $results, $total, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new helper-aware paginator instance
     * @param \Illuminate\Support\Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        // return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
        //     'items', 'total', 'perPage', 'currentPage', 'options'
        // ));

        return Container::getInstance()->makeWith(SpecialPaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
