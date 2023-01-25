<?php

namespace App\Console\Commands;

use App\Services\ChristmasBasketShippingAddressReport;
use App\Services\ChristmasBasketUpdateAddressReport;
use Illuminate\Console\Command;

class GenerateChristmasBasketReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report:christmas-basket';

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
    public function handle()
    {
        $reportServiceShippingAdress = new ChristmasBasketShippingAddressReport();
        if ($path = $reportServiceShippingAdress->generateReport()) {
            $this->info($path);
        } else {
            $this->info('Erro ao criar arquivo de relatorio');
        }
    }
}
