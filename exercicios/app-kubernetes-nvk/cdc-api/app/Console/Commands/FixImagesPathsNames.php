<?php

namespace App\Console\Commands;

use App\Magazine;
use App\Message;
use App\User;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FixImagesPathsNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:fix-names {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix relative paths for database entries with locally stored images. Returns full URL to maintain coding style with we use external image provider.';

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
        $conditions = [
            'laravel-backup - Backup dos arquivos',
            'mysqldump - Backup do BD'
        ];

        $requirements = $this->choice(
            'Você executou essas pré-condições antes de rodar esse comando?',
            [
                'laravel-backup - Backup dos arquivos',
                'mysqldump - Backup do BD'
            ],
            null,
            1,
            true
        );

        $this->info(json_encode($requirements));

//        $force = $this->option('force');
//
//        if (!$force) {
//            $this->info($requirements);
//        }

        return true;

        /*
         * get all links from database on records:
         *  - users->avatar (remove default entry behavior => defaults to NULL)
         *  - magazines->cover (remove default entry behavior => defaults to NULL)
         *  - messages->{description} (full path returned from quill image-uploader => enforce https on Storage::url, fix .env APP_URL)
         *
         * search links ocurrences and replace `http://api.casadiconti.com.br/storage` with `https://api.casadiconti.com.br/storage`
         * update users and magazine models to not include a default image
         *      - update migration/factory/seeds: 'avatar' and 'cover' are nullable fields without a default value
         *      - update form requests: 'avatar' and 'cover' fields are not required anymore
         *
         * rename/move uploaded images to REMOVE special chars (should rename filename and its entry on DB)
         */

        $avatars = User::all();
        $avatars->each(function ($model) {
            $url = $model->getOriginal('avatar');

            // if it have default value, set as null
            if (strpos($url, "/assets/icon/default-avatar.jpg") !== false ||
                strpos($url, "users-avatars/default-avatar.jpg") !== false) {
                $newUrl = null;
            } else {
                $newUrl = $this->moveAndRenameFileUrl($url);
            }

            if ($newUrl !== false) {
                if (!is_null($newUrl)) $this->info($newUrl);
                $model->avatar = $newUrl;
                if(!$model->save()) {
                    $this->info("Falha ao salvar: user(".$model->id.") ".$url);
                }
            }
        });

        $covers = Magazine::all();
        $covers->each(function ($model) {
            $url = $model->getOriginal('cover');

            if (strpos($url, "magazines-covers/default-cover.png") !== false) {
                $newUrl = null;
            } else {
                $newUrl = $this->moveAndRenameFileUrl($url);
            }

            if ($newUrl !== false) {
                if (!is_null($newUrl)) $this->info($newUrl);
                $model->cover = $newUrl;
                if (!$model->save()) {
                    $this->info("Falha ao salvar: magazine(" . $model->id . ") " . $url);
                }
            }
        });

        // get all messages with uploaded images
        $quillUploadedImages = Message::where('description', 'like', '%/messages-images/%')->get();
        $quillUploadedImages->each(function ($model) {
            $description = $model->getOriginal('description');
            $newDescription = $description;

            // LIST ONLY IMAGE URLS FROM DESCRIPTION
            $content = mb_convert_encoding($description, 'HTML-ENTITIES', 'UTF-8');
            $htmlDom = new DOMDocument('1.0', 'utf-8');
            $htmlDom->LoadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $imageTags = $htmlDom->getElementsByTagName('img');
            foreach ($imageTags as $imageTag) {
                $imgSrc = $imageTag->getAttribute('src');
                // ignore emojis images from gmail
                if (strpos($imgSrc, 'mail.google.com/mail/e') === false) {
                    $newSrc = $this->moveAndRenameFileUrl($imgSrc);
                    if ($newSrc) {
                        $this->info($newSrc);
                        $newDescription = str_replace($imgSrc, $newSrc, $newDescription);
                    }
                }
            }

            $model->description = $newDescription;
            if(!$model->save()) {
                $this->info("Falha ao salvar: message(".$model->id.") ".$model->title);
            }

        });

        $this->info('Atualização dos links finalizada');

        return true;
    }

    private function moveAndRenameFileUrl($url = false)
    {
        if(!$url || is_null($url))
            return false;

        $removes = [
            "http://api.casadiconti.com.br/storage/",
            "https://api.casadiconti.com.br/storage/",
            url("storage")."/",
            secure_url("storage")."/"
        ];
        $path = str_replace($removes, ["", "", "", ""], $url);

        // if file isnt on public storage OR $url and $path are same after cleaning with $removes
        if (!Storage::disk('s3')->exists($path) || strcmp($url, $path) === 0) {
            $this->info('Arquivo não encontrado no local: '.$url);
            return false;
        }

        $dir = File::dirname($path);
//        $name = File::name($path);
        $extension = File::extension($path);

        $newName = Str::random(40); // same as FileHelper::hashName
        $newPath = $dir.'/'.$newName.'.'.$extension;
        if (Storage::move($path, $newPath)) {
            return Storage::url($newPath);
        } else {
            $this->info('Erro ao mover arquivo: '.$url);
        }

        return false;
    }

}
