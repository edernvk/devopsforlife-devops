<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class OptimizeSavedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all saved images through default options of spatie/image-optimizer package';

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

        /*
         * get all images from
         *  - magazines-covers
         *  - messages-images
         *  - users-avatars
         *
         * run all of them through spatie/image-optimizer
         */

        $magazinesCover = Storage::files('magazines-covers');
        $messagesImages = Storage::files('messages-images');
        $usersAvatar = Storage::files('users-avatars');

        $images = array_merge($magazinesCover, $messagesImages, $usersAvatar);
        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        foreach($images as $img) {
            $filePath = Storage::disk('s3')->path($img);
            ImageOptimizer::optimize($filePath);
            $bar->advance();
        }

        $bar->finish();
        $this->info(' Imagens otimizadas com sucesso!');

        return true;
    }
}
