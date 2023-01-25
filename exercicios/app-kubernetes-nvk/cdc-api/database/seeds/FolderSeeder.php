<?php

use App\Folder;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Folder::class)->create();
        factory(Folder::class)->create(['name' => 'Juridico']);
        factory(Folder::class)->create(['name' => 'Tecnologia']);
        factory(Folder::class)->create(['name' => 'Recursos Humanos']);
    }
}
