<?php

namespace App\Console\Commands;

use App\User;
use Conti\Interfaces\ContiInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RequestCdcApiWorkers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdc:api';

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
//    public function handle(ContiInterface $conti)
    public function handle()
    {
        $users = User::doesntHave('christmas_token')->approved()->withoutGhosts()->count();
        dump($users);
        return 0;

        $funcs = $conti->getFuncionarios();
        $funcsCpf = $funcs->lazy()->pluck('cpf');

        $usersNoToken = User::select('name', 'cpf')->approved()->withoutGhosts()->doesntHave('christmas_token')->get();
        $this->info('Users no token: '. $usersNoToken->count());
        $usersCpf = $usersNoToken->pluck('cpf');

        $matchedUsers = $funcs->lazy()->whereIn('cpf', $usersCpf);
        $notMatchedUsers = $usersNoToken->lazy()->whereNotIn('cpf', $funcsCpf);

        $this->info('Users matched from api: '. $matchedUsers->count());
        $this->info('Users not matched from api: '. $notMatchedUsers->count());

        dump($notMatchedUsers->toArray());

        return 0;
    }
}
