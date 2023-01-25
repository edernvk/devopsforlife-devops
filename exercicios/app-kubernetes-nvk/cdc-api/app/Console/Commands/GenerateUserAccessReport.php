<?php

namespace App\Console\Commands;

use App\Services\ReportService;
use App\User;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateUserAccessReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report:user-access {--S|start=} {--E|end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a csv report file with user access in date interval';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = $this->option('start');
        $end = $this->option('end');
        $this->info($start.' - '.$end);

//        $acessosRaw = collect(DB::select(DB::raw(
//            "SELECT
//              DATE(al.created_at) AS date,
//              causer_type,
//              causer_id,
//              COUNT(*) AS acessos
//            FROM activity_log AS al
//            INNER JOIN users AS u ON al.causer_id = u.id
//            WHERE causer_type = 'App\\\User'
//            AND al.created_at BETWEEN '2020-05-01 00:00:00' AND '2020-05-03 23:59:59'
//            AND u.cpf NOT IN ('99999999990', '99999999991', '99999999999')
//            GROUP BY date, causer_type, causer_id
//            ORDER BY date, causer_id")));

        $reportService = new ReportService();

        $filePath = $reportService->generateUserAccessBasename($start, $end);
        if (Storage::disk('s3')->exists($filePath.'.csv')) {
            $this->info('Este relatório já foi gerado.');
            return true;
        }

        if ($path = $reportService->generateUserAccess($start, $end)) {
            $this->info($path);
        } else {
            $this->info('Erro ao criar o arquivo do relatório');
        }

        return true;
    }
}
