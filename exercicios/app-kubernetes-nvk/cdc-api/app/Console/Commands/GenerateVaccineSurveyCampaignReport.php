<?php

namespace App\Console\Commands;

use App\Services\VaccineSurveyCampaignReport;
use Conti\Interfaces\ContiInterface;
use Illuminate\Console\Command;

class GenerateVaccineSurveyCampaignReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report:vaccine-survey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(ContiInterface $conti)
    {
        $reportService = new VaccineSurveyCampaignReport($conti);

        if ($path = $reportService->generateEntriesReport()) {
            $this->info($path);
        } else {
            $this->info('Erro ao criar o arquivo do relatório');
            return 0;
        }

        return 1;
    }
}
