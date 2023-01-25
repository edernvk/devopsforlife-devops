<?php

namespace App\Console\Commands;

use App\User;
use Conti\Interfaces\ContiInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddEmployeeContiByApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:employee_conti';

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
        $funcs = $conti->getFuncionarios();

        $funcs->each(function ($func) {
            $user = User::where('cpf', $func->cpf)->first();
            if ($user) {
                DB::table('employee_conti')->insert([
                    'user_id' => $user->id,
                    'admission_date' => $func->data_admissao,
                    'situation' => $func->desc_situacao,
                    'company_subsidiary' => $func->nome_filial,
                    'vcard_phone' => $func->fone_cartaovirtual,
                    'vcard_address' => $func->local_cartaovirtual,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
    }
}
