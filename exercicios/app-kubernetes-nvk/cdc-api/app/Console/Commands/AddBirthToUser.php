<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\User;

class AddBirthToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:birthdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add birthdate to users at field birth_date';

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
        $json = File::get('database/data/birthday.json');
        $data = json_decode($json);

        foreach($data as $obj){
            $this->info($obj->cpf);
            $user = User::where('cpf', '=', $obj->cpf)->first();
            
            if($user){
                $user->birth_date = $obj->data_nasc;
                $user->save();
            }else{
                $this->info("Usuario nao encontrado ".$obj->cpf);
            }
        }
       
    }
}
