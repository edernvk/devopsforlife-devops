<?php

use Illuminate\Database\Seeder;
use App\Role;

class MockingStressTestUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'testing'])) {

            $roleColaborator  = Role::where('name', 'Colaborador')->first();

            factory(App\User::class, 1000)->make()->each(function ($user) use ($roleColaborator) {

                if (random_int(0,5) >= 3) {
                    $user->approved = null;
                }
                $user->save();
                $user->roles()->attach($roleColaborator);

            });

        }
    }
}
