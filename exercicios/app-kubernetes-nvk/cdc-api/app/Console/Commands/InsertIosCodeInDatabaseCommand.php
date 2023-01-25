<?php

namespace App\Console\Commands;

use App\IosCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InsertIosCodeInDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:ios-codes';

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
        $this->info('Salvar os codigos ios no banco de dados');

        $path = storage_path('app');
        $file = File::get($path . '/code-ios.json');

        $codes = collect(json_decode($file, true));

        $codes->each(function ($code, $index) {
            $this->info("Salvando o $index codigo");
            IosCode::create([
                'code' => $code['code'],
                'link' => $code['link']
            ]);
        });

        $this->info('Codigos salvos com sucesso');
    }
}
