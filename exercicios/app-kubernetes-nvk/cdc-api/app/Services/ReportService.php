<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use App\Exports\ReportExport;
use App\User;

class ReportService
{

    public function generateUserAccess($start, $end) {

        $period = CarbonPeriod::create($start, $end);

        $period_days = array_map(function($item) {
            return $item->format('Y-m-d');
        }, $period->toArray());

        // these users should not be reported
        // they are for Webmasters, Bots or Review Accounts
        $ghostUsers = [
            99999999990,
            99999999991,
            99999999992,
            99999999993,
            99999999994,
            99999999995,
            99999999996,
            99999999997,
            99999999998,
            99999999999
        ];

        $acessos = DB::table('activity_log')
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->select(DB::raw('DATE(activity_log.created_at) AS date, causer_type, causer_id, COUNT(*) AS acessos'))
            ->where('causer_type', 'App\\User')
            ->whereBetween('activity_log.created_at', [$period->getStartDate()->startOfDay(), $period->getEndDate()->endOfDay()])
            ->whereNotIn('users.cpf', $ghostUsers)
            ->groupBy('date', 'causer_type', 'causer_id')
            ->orderBy('date', 'asc')
            ->get();

        $acessos_arr = $acessos->map(function ($item) {
            return (array) $item;
        });

        $indexed_by_date = [];
        foreach($acessos_arr as $item) {
            $indexed_by_date[$item['date']][] = $item['causer_id'];
        }

        $basename = $this->generateUserAccessBasename($start, $end);
        $headers = array_merge(array("cpf","nome"), $period_days);
        $entries = [];

        $users = User::whereNotIn('cpf', $ghostUsers)->where('approved', '!=', null)->orderBy('name')->get(['id','cpf','name']);
        foreach ($users as $user) {
            $row = [];
            $row[] = $user->cpf;
            $row[] = $user->name;
            foreach ($period_days as $day) {
                if (array_key_exists($day, $indexed_by_date)) {
                    if (in_array($user->id, $indexed_by_date[$day])) {
                        $row[] = 1;
                    } else {
                        $row[] = 0;
                    }
                } else {
                    $row[] = 0;
                }
            }
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);

    }

    public function generateUserAccessBasename($start, $end) {
        return 'reports/user-access/user-access-'.$start.'-'.$end;
    }

    public function generateUserAccessDownloadName($start, $end) {
        return 'cdcdigital-acesso-de-usuarios-'.$start.'--'.$end;
    }

    public function countUsersAccess($start, $end, $type) {
        $period = CarbonPeriod::create($start, $end);

        $period_days = array_map(function($item) {
            return $item->format('Y-m-d');
        }, $period->toArray());

        // these users should not be reported
        // they are for Webmasters, Bots or Review Accounts
        $ghostUsers = [
            99999999990,
            99999999991,
            99999999992,
            99999999993,
            99999999994,
            99999999995,
            99999999996,
            99999999997,
            99999999998,
            99999999999
        ];

        $acessos = DB::table('activity_log')
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->select(DB::raw('DATE(activity_log.created_at) AS date, causer_type, causer_id, COUNT(*) AS acessos'))
            ->where('causer_type', 'App\\User')
            ->whereBetween('activity_log.created_at', [$period->getStartDate()->startOfDay(), $period->getEndDate()->endOfDay()])
            ->whereNotIn('users.cpf', $ghostUsers)
            ->groupBy('date', 'causer_type', 'causer_id')
            ->orderBy('date', 'asc')
            ->get();

        $acessos_arr = $acessos->map(function ($item) {
            return (array) $item;
        });

        $indexed_by_date = [];
        foreach($acessos_arr as $item) {
            $indexed_by_date[$item['date']][] = $item['causer_id'];
        }

        $numbers_access = 0;
        $users = User::whereNotIn('cpf', $ghostUsers)->where('approved', '!=', null)->orderBy('name')->get(['id','cpf','name']);
        foreach ($users as $user) {
            $count_access = 0;
            $count_not_access = 0;
            foreach ($period_days as $day) {
                if (array_key_exists($day, $indexed_by_date)) {
                    if (in_array($user->id, $indexed_by_date[$day])) {
                        $count_access += 1;
                    } else {
                        $count_not_access += 1;
                    }
                } else {
                    $count_not_access += 1;
                }
            }

            if ($count_not_access > 0 && $count_access == 0) {
                $count_not_access = 1;
            } else {
                $count_not_access = 0;
            }

            if ($type == 'access'){
                $numbers_access += $count_access;
            } else if ($type == 'not_accessed') {
                $numbers_access += $count_not_access;
            }
        }

        return $numbers_access;
    }

}
