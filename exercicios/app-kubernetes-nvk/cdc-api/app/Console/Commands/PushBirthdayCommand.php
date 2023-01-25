<?php

namespace App\Console\Commands;

use App\Services\PushService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PushBirthdayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command send push notification to birthday users';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PushService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today()->format('d-m');
        $birthdayUser = User::where('birth_date', $today)
                                ->where('received_notification_birthday', true)
                                ->get('id');

        $userIds = $birthdayUser->map(function ($user) {
            return (string) $user->id;
        })->toArray(); 

        try {
            $this->service->sendToExternalUser(
                "Feliz aniversário! 🎉🎈🎂",
                "Hoje o nosso brinde é dedicado a você! A Casa Di Conti tem orgulho em poder celebrar contigo, que seu dia seja de muita felicidade e boas energias.",
                "/aniversariantes",
                $userIds
            );
        } catch (\Exception $th) {
            $this->info("Ocorreu um erro: ". $th->getMessage());
        }
    }
}
