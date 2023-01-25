<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Navigation;

class NavigationController
{
    public function __invoke()
    {
        $navigation = Navigation::all();

        $currentActiveNavigation = $navigation->filter(function ($item) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $item->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $item->end_date)->endOfDay();

            return \Carbon\Carbon::now()->betweenIncluded($startDate, $endDate);
        })->map(function($item) {
            if ($item->needs_role) {
                $item->roles = explode('|', $item->roles);
            }
            return $item;
        })->values();

        return $currentActiveNavigation;
    }
}
