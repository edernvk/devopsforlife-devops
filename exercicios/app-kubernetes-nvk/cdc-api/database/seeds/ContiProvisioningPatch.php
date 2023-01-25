<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContiProvisioningPatch extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['staging'])) {
            DB::table('teams')->delete();
            DB::table('teams')->truncate();
            DB::table('roles')->delete();
            DB::table('roles')->truncate();
            DB::table('users')->delete();
            DB::table('users')->truncate();
            DB::table('role_user')->delete();
            DB::table('role_user')->truncate();
            DB::table('messages')->delete();
            DB::table('messages')->truncate();
            DB::table('messages_users')->delete();
            DB::table('messages_users')->truncate();

            $this->call(UserTableSeeder::class);
            $this->call(PenzeUsersSeed::class);

            $this->call(ContiProvisioningTeamsUsers::class);
            $this->call(ContiProvisioningWelcomingMessages::class);
        }
    }
}
