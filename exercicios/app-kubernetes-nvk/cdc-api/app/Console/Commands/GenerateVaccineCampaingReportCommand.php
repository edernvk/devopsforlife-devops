<?php

namespace App\Console\Commands;

use App\Services\VaccineCampaignReport;
use Illuminate\Console\Command;

class GenerateVaccineCampaingReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report:vaccine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate vaccine campaing report';

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
        $report = new VaccineCampaignReport();
        if ($path = $report->generateReport()) {
            $this->info($path);
        }else {
            $this->info('Erro ao criar o arquivo do relatório');
            return 0;
        }

        return 1;
    }
}
