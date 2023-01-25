<?php

namespace App\Console\Commands;

use App\Services\CupShirtCampaingReport;
use App\Services\UpdatersCupShirtCampaingReport;
use Illuminate\Console\Command;

class GenerateCupShirtOrdersByUsersReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report:cupshirt:order-by-user';

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
    public function handle(CupShirtCampaingReport $reportService)
    {
        if ($path = $reportService->generatePedidosPorUsuarioReport()) {
            $this->info($path);
        } else {
            $this->info('Erro ao criar o arquivo do relatório');
            return 0;
        }

        return 1;
    }
}
