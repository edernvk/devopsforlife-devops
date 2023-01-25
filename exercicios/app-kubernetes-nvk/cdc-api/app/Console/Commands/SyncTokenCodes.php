<?php

namespace App\Console\Commands;

use App\User;
use Conti\Interfaces\ContiInterface;
use Illuminate\Console\Command;

class SyncTokenCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:sync:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize tokes from API with users';

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
        // identificar usuários local que não tem token
        // verificar se usuário existe na listagem da API
        // criar token para ele

        $usersNoToken = User::select('id', 'name', 'cpf')->doesntHave('christmas_token')->approved()->withoutGhosts()->get();
        $this->info('Usuários sem token: '. $usersNoToken->count());
        $usersCpf = $usersNoToken->pluck('cpf');

        if ($usersNoToken->count() === 0) {
            $this->info('Nenhum usuário cadastrado sem token.');
            return 1;
        }

        $funcs = $conti->getFuncionarios();
        $funcsCpf = $funcs->lazy()->pluck('cpf');

        $matchedUsers = $funcs->lazy()->whereIn('cpf', $usersCpf);
        $notMatchedUsers = $usersNoToken->lazy()->whereNotIn('cpf', $funcsCpf);

        $this->info('Usuários encontrados na API: '. $matchedUsers->count());
        $this->info('Usuários não encontrados na API: '. $notMatchedUsers->count());
        dump($notMatchedUsers->toArray());

        if ($usersNoToken->count() > 0 && $matchedUsers->count() === 0) {
            $this->info('Usuários sem token não encontrados na API.');
            return 1;
        }

        $latestId = \App\ChristmasToken::latest('id')->first('id')->id;

        $matchedUsers->each(function ($apiUser) {
            $cpfNumber = $this->onlyNumbers($apiUser->cpf ?? $apiUser['cpf']);
            $cpfWithoutPad = ltrim($cpfNumber, '0');
            $cpfWithPad = false;
            if (strlen($cpfNumber) < 11) {
                $cpfWithPad = str_pad($cpfNumber, 11, '0', STR_PAD_LEFT);
            }

            $user = \App\User::where('cpf', $cpfNumber)
                ->orWhere('cpf', $cpfWithoutPad)
                ->when($cpfWithPad, function ($q, $cpfWithPad) {
                    return $q->orWhere('cpf', $cpfWithPad);
                })
                ->first();

            if ($user) {
                $new = \App\ChristmasToken::create([
                    'cpf' => $cpfNumber,
                    'token' => $apiUser->token ?? $apiUser['token'],
                    'user_id' => $user->id
                ]);
            }
        });

        $newEntries = \App\ChristmasToken::where('id', '>', $latestId)->get();
        $this->info('Novos registros: '. $newEntries->count());

        return 0;
    }

    private function onlyNumbers($value) {
        return preg_replace("/[^A-Za-z0-9]/", '', $value);
    }
}
