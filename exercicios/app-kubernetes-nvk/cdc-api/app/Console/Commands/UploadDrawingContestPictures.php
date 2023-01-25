<?php

namespace App\Console\Commands;

use App\DrawingContestCategory;
use App\DrawingContestPicture;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UploadDrawingContestPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:pictures
                            {--reset : Whether or not we should remove/clear previous files and reset the database }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload drawign contest pictures';

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
        $this->info('Upload pintando CasaDiConti');

        if ($this->option('reset')) {
            $this->info('Esta ação irá excluir as imagens já carregadas e também irá excluir e resetar os dados salvos (categorias, imagens e votos) no banco de dados.');
            if ($this->confirm('Você tem certeza que quer excluir esses dados?')) {
                $this->removePreviousUploadedImages();
                $this->truncateAndResetDatabaseTables();
            }
        }

        $publicDrawings = storage_path('app/public/drawing-contest/');
        File::ensureDirectoryExists($publicDrawings);

        $directories = Storage::disk('s3')->directories('drawing-contest');
        natsort($directories);

        $directoriesCollection = collect($directories);

        $directoriesCollection->each(function($directory) {
            $categoryName = basename($directory);
            $files = Storage::disk('s3')->files($directory);

            $category = DrawingContestCategory::firstOrCreate([
                'name' => $categoryName
            ]);

            if (count($files) > 0) {
                natsort($files);

                foreach ($files as $file) {
                    $filePath = storage_path('app/'.$file);
                    $fileInfo = pathinfo($filePath);

                    $hashedName = storage_path('app/public/drawing-contest/').time().rand(1,100).basename($file);
                    $saved = File::move($filePath, $hashedName);
                    if ($saved) {
                        $newPublicUrl = Storage::url('drawing-contest/'. basename($hashedName));
                        DrawingContestPicture::create([
                            'category_id' => $category->id,
                            'url' => $newPublicUrl,
                            'subscription' => $fileInfo['filename']
                        ]);
                    }
                }

                $this->info("$categoryName - Upload completo");
            } else {
                $this->info("$categoryName - Nenhuma imagem para salvar");
            }

        });

        $this->info('FIM');
    }

    private function removePreviousUploadedImages() {
        $directory = storage_path('app/public/drawing-contest/');
        if (File::deleteDirectory($directory)) {
            $this->info('Imagens removidas');
            File::makeDirectory($directory);
        }
    }

    private function truncateAndResetDatabaseTables() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('drawing_contest_votes')->delete();
        DB::table('drawing_contest_votes')->truncate();
        DB::table('drawing_contest_pictures')->delete();
        DB::table('drawing_contest_pictures')->truncate();
        DB::table('drawing_contest_categories')->delete();
        DB::table('drawing_contest_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Tabelas no banco de dados limpas e resetadas');
    }

}
