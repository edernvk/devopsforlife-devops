<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $roleEmployee = new Role();
        $roleEmployee->name = 'Colaborador';
        $roleEmployee->description = "Colaborador da Casa di Conti";
        $roleEmployee->save();

        $roleManager = new Role();
        $roleManager->name = 'Administrador';
        $roleManager->description = "Administrador do CDC Digital";
        $roleManager->save();
    }
}
