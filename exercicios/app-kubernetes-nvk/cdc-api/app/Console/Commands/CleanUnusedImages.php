<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Magazine;
use App\Message;
use App\User;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all images saved on database entries, compare with all saved images on disk and delete saved images not used anymore ("not used": related entry might already be deleted, uploaded image might not be the one selected, etc)';

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
         * save full backup
         *
         * get all links from database on records:
         *  - users->avatar
         *  - magazines->cover
         *  - messages->{description}
         *
         * get all images on storage:
         *  - users-avatars
         *  - magazines-covers
         *  - messages-images
         *
         * delete all images without entry on database
         *
         * save report on storage folder
         */

        $usedImages = collect();
        $avatarImages = collect();
        $magazineImages = collect();
        $messageImages = collect();

        // ignore file ext in like statement to prevent confusion between png or jpg
//        $avatarDefaults = User::where('avatar', 'like', '%/default-avatar%')->pluck('id');
//        $avatars = User::whereIntegerNotInRaw('id', $avatarDefaults)->get(['avatar']);
        $avatars = User::distinct()->get(['avatar']);
        $avatars->each(function ($model) use ($usedImages, $avatarImages) {
            $url = $model->getOriginal('avatar');
            $clean = $this->getCleanPath($url);
            if ($clean) {
                $usedImages->push($clean);
                $avatarImages->push($clean);
            }
        });

        // ignore file ext in like statement to prevent confusion between png or jpg
//        $magazineDefaults = Magazine::where('cover', 'like', '%/default-cover%')->pluck('id');
//        $covers = Magazine::whereIntegerNotInRaw('id', $magazineDefaults)->get(['cover']);
        $covers = Magazine::distinct()->get(['cover']);
        $covers->each(function ($model) use ($usedImages, $magazineImages) {
            $url = $model->getOriginal('cover');
            $clean = $this->getCleanPath($url);
            if ($clean) {
                $usedImages->push($clean);
                $magazineImages->push($clean);
            }
        });

        $quillUploadedImages = Message::where('description', 'like', '%/messages-images/%')->get(['id', 'description']);
        $quillUploadedImages->each(function ($model) use ($usedImages, $messageImages) {
            $description = $model->getOriginal('description');

            $content = mb_convert_encoding($description, 'HTML-ENTITIES', 'UTF-8');
            $htmlDom = new DOMDocument('1.0', 'utf-8');
            $htmlDom->LoadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $imageTags = $htmlDom->getElementsByTagName('img');

            foreach ($imageTags as $imageTag) {
                $imgSrc = $imageTag->getAttribute('src');
                // ignore emojis images from gmail
                if (strpos($imgSrc, 'mail.google.com/mail/e') === false) {
                    $clean = $this->getCleanPath($imgSrc);
                    if ($clean) {
                        $usedImages->push($clean);
                        $messageImages->push($clean);
                    }
                }
            }
        });

        // filter images tagged for the front-end (IT'S WRONG, IT SHOULD NOT SAVE LIKE THAT!)
        // defaults and bot image
//        $filters = array("/default-cover", "/default-avatar", "/assets/");
        $pathsToRemove = array("/assets/");
        // remove $pathsToRemove entries from $usedImages
        $cleanUsedImages = $usedImages->reject(function ($img, $key) use ($pathsToRemove) {
            foreach ($pathsToRemove as $filter) {
                if (strpos($img, $filter) !== false) {
                    return true;
                }
            }
            return false;
        })->values();

        $usersAvatar = Storage::files('users-avatars');
        $magazinesCover = Storage::files('magazines-covers');
        $messagesImages = Storage::files('messages-images');
        $allImages = collect(array_merge($usersAvatar, $magazinesCover, $messagesImages));

        $defaults = array("/default-cover.", "/default-avatar.", "/bot.");
        // remove $defaults entries from $allImages
        $savedImages = $allImages->reject(function ($img, $key) use ($defaults) {
            foreach ($defaults as $default) {
                if (strpos($img, $default) !== false) {
                    return true;
                }
            }
            return false;
        })->values();

        $notUsed = $savedImages->diff($cleanUsedImages);

//        /* USED JUST TO DEBUG */
//
//        $used = $savedImages->intersect($filteredImages);
//
//        $this->info('Saved: '.$savedImages->count());
//        $this->info('Filtered: '.$filteredImages->count());
//        $this->info('Not used: '.$notUsed->count());
//        $this->info('Used: '.$used->count());
//
//        $this->info('Não usados:');
//        foreach($notUsed->values() as $entry) {
//            $type = explode('/', $entry)[0];
//
//            switch($type) {
//                case 'users-avatars':
//                    $user = User::where('avatar', 'like', '%'.$entry.'%')->first();
//                    ($user) ? $this->info('usage user: '.$user->id) : null;
//                    break;
//                case 'magazines-covers':
//                    $magazine = Magazine::where('cover', 'like', '%'.$entry.'%')->first();
//                    ($magazine) ? $this->info('usage magazine: '.$magazine->id) : null;
//                    break;
//                case 'messages-images':
//                    $message = Message::where('description', 'like', '%'.$entry.'%')->first();
//                    ($message) ? $this->info('usage message: '.$message->id) : null;
//                    break;
//                default:
//                    $this->info('invalido');
//                    break;
//            }
//        }
//
//        $this->info('Usados:');
//        foreach($used->values() as $entry) {
//            $type = explode('/', $entry)[0];
//
//            switch($type) {
//                case 'users-avatars':
//                    $user = User::where('avatar', 'like', '%'.$entry.'%')->first();
//                    (!$user) ? $this->info('no user: '.$entry) : null;
//                    break;
//                case 'magazines-covers':
//                    $magazine = Magazine::where('cover', 'like', '%'.$entry.'%')->first();
//                    (!$magazine) ? $this->info('no magazine: '.$entry) : null;
//                    break;
//                case 'messages-images':
//                    $message = Message::where('description', 'like', '%'.$entry.'%')->first();
//                    (!$message) ? $this->info('no message: '.$entry) : null;
//                    break;
//                default:
//                    $this->info('invalido');
//                    break;
//            }
//        }
//
//        /* --- END - DEBUG --- */

        // check if $notUsed image exists on storage (might be
        $filesToRemove = $notUsed->filter(function ($img) {
            $r = Storage::disk('s3')->exists($img);
            ($r) ? $this->info('Found: '.$img) : $this->info('Not found: '.$img);
            return $r;
        });

        if ($filesToRemove->count() > 0) {
            $deleted = Storage::disk('s3')->delete($filesToRemove->values()->toArray());

            $this->saveReport('removed-images', $filesToRemove->values());
        } else {
            $this->info('Não há imagens a serem excluídas.');
        }

        return true;
    }

    private function saveReport($name, $content)
    {
        $path = $this->createFilepath($name);
        Storage::disk('s3')->put($path, json_encode($content, JSON_PRETTY_PRINT));
        $this->info('Relatório salvo em: ' . $path);
    }

    private function createFilepath($resourceName)
    {
        $filenamePrefix = Carbon::now()->format('Ymd-His') . '_' . $resourceName;
        return 'maintenance-dumps/images/' . $filenamePrefix . '.json';
    }

    private function getCleanPath($url = false)
    {
        if(!$url || is_null($url))
            return false;

        $removes = [
            "http://api.casadiconti.com.br/storage/",
            "https://api.casadiconti.com.br/storage/",
            url("storage")."/",
            secure_url("storage")."/"
        ];

        $clean = str_replace($removes, ["", "", "", ""], $url);
        // return $clean path only if local (if it $removes nothing, it's not in public storage)
        return (strcmp($url, $clean) !== 0) ? $clean : false;
    }
}
