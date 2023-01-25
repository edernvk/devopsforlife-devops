<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\City;
use App\Role;
use App\Team;
use Illuminate\Support\Facades\Storage;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $team = Team::create(['name' => 'Penze/Lectuz']);
        $roleManager = Role::where('name', 'Administrador')->first();
        $assisCity = City::where('name', "Assis")->first();

        $admin = new User();
        $admin->name = "Administrador";
        $admin->email = "admin@penze.com.br";
        $admin->registration = "9999999990";
        $admin->mobile = "18999999999";
        $admin->cpf = "99999999990";
        $admin->password = Hash::make('penze20181900');
        $admin->city_id = $assisCity->id;
        $admin->approved = now();
        $admin->team_id = $team->id; // Penze/Lectuz
        $admin->save();
        $admin->roles()->attach($roleManager);

        $bot = new User();
        $bot->name = "Bot CDC digital 🤖";
        $bot->email = "bot@penze.com.br";
        $bot->registration = "9999999991";
        $bot->mobile = "(18) 99658-0203";
        $bot->cpf = "99999999991";
        $bot->avatar = Storage::url('users-avatars/bot.png');
        $bot->password = Hash::make('penze20181900');
        $bot->city_id = $assisCity->id;
        $bot->approved = now();
        $bot->team_id = $team->id; // Penze/Lectuz
        $bot->save();
        $bot->roles()->attach($roleManager);
    }
}
