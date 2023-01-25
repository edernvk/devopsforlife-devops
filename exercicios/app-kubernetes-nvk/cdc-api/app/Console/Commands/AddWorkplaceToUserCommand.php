<?php

namespace App\Console\Commands;

use App\User;
use Conti\Interfaces\ContiInterface;
use Illuminate\Console\Command;

class AddWorkplaceToUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:workplace';

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

        $funcs->each(function($func) {
           $user = User::where('cpf', $func->cpf)->first();
           if ($user) {
               $user->workplace = $func->desc_local;
               $user->save();
           } else {
               $this->info("Usuario não encontrado");
           }
        });
    }
}
