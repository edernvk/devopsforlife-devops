<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class PasswordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/password-2020-05-15.json');
        $data = json_decode($json);

        foreach($data->users as $singleUser) {
            $user = User::where('cpf', $singleUser->cpf)->first();
            if($user) {
                $user->password = Hash::make($singleUser->senha);
                $user->save();
            }
        }
    }
}
