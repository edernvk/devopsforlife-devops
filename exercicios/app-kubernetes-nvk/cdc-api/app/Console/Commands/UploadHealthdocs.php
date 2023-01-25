<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;

class UploadHealthdocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:healthdocs {--D|docs=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Upload users' healthdocs";

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
        $docsFolder = $this->option('docs');

        switch($docsFolder) {
            case 'unimed':
                $unimedTitle = 'Resultados - Unimed - 2020';
                $unimed = storage_path('app/upload-healthdocs/unimed/');
                $filesUnimed = File::files($unimed);

                $this->info('Exames Unimed: '.count($filesUnimed));
                $unimedDBBatch = [];
                foreach($filesUnimed as $file) {
                    $now = Carbon::now();
                    $pdfName = basename($file);
                    $onlyCpf = Str::substr($pdfName, 0, 11);
                    $user = User::where('cpf', $onlyCpf)->first();

                    if ($user) {
                        $hashedName = storage_path('app/public/users-healthdocs/').time().rand(1,100).basename($file);
                        $response = File::move($file, $hashedName);
                        $newPublicFilepath = Storage::url('users-healthdocs/'.basename($hashedName));

                        $unimedDBBatch[] = [
                            'user_id' => $user->id,
                            'url_doc' => $newPublicFilepath,
                            'title' => $unimedTitle,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];

                    } else {
                        $this->info('Arquivo não tem usuário cadastrado: '.$pdfName);
                    }
                }
                if (count($unimedDBBatch) > 0) {
                    DB::table('healthdocs')->insert($unimedDBBatch);
                } else {
                    $this->info('Não há registros para serem salvos');
                }
                $this->info('Arquivos Unimed cadastrados');

                break;

            case 'lab':
                $labTitle = 'Resultados - Laboratório - 2020';
                $lab = storage_path('app/upload-healthdocs/laboratorio/');
                $filesLab = File::files($lab);

                $this->info('Exames Laboratório: '.count($filesLab));
                $laboratorioDBBatch = [];
                foreach($filesLab as $file) {
                    $now = Carbon::now();
                    $pdfName = basename($file);
                    $onlyCpf = Str::substr($pdfName, 0, 11);
                    $user = User::where('cpf', $onlyCpf)->first();

                    if ($user) {
                        $hashedName = storage_path('app/public/users-healthdocs/').time().rand(1,100).basename($file);
                        $response = File::move($file, $hashedName);
                        $newPublicFilepath = Storage::url('users-healthdocs/'.basename($hashedName));

                        $laboratorioDBBatch[] = [
                            'user_id' => $user->id,
                            'url_doc' => $newPublicFilepath,
                            'title' => $labTitle,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];

                    } else {
                        $this->info('Arquivo não tem usuário cadastrado: '.$pdfName);
                    }
                }
                if (count($laboratorioDBBatch) > 0) {
                    DB::table('healthdocs')->insert($laboratorioDBBatch);
                } else {
                    $this->info('Não há registros para serem salvos');
                }
                $this->info('Arquivos Laboratório cadastrados');

                break;

            case 'carta':
                $docTitle = 'Carta de Recomendações Médicas - 2020';
                $doc = storage_path('app/upload-healthdocs/carta/');
                $filesDoc = File::files($doc);

                $this->info('Carta de Recomendações Médicas: '.count($filesDoc));
                $DBBatch = [];
                foreach($filesDoc as $file) {
                    $now = Carbon::now();
                    $pdfName = basename($file);
                    $onlyCpf = Str::substr($pdfName, 0, 11);
                    $user = User::where('cpf', $onlyCpf)->first();

                    if ($user) {
                        $hashedName = storage_path('app/public/users-healthdocs/').time().rand(1,100).basename($file);
                        $response = File::move($file, $hashedName);
                        $newPublicFilepath = Storage::url('users-healthdocs/'.basename($hashedName));

                        $DBBatch[] = [
                            'user_id' => $user->id,
                            'url_doc' => $newPublicFilepath,
                            'title' => $docTitle,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];

                    } else {
                        $this->info('Arquivo não tem usuário cadastrado: '.$pdfName);
                    }
                }
                if (count($DBBatch) > 0) {
                    DB::table('healthdocs')->insert($DBBatch);
                } else {
                    $this->info('Não há registros para serem salvos');
                }
                $this->info('Arquivos Carta cadastrados');

                break;

            case 'cardapio':
                $docTitle = 'Cardápio - Programa Quero Viver';
                $doc = storage_path('app/upload-healthdocs/cardapio/');
                $filesDoc = File::files($doc);

                $this->info('Cardápio: '.count($filesDoc));
                $DBBatch = [];
                foreach($filesDoc as $file) {
                    $now = Carbon::now();
                    $pdfName = basename($file);
                    // ASSUME PDF NAME IS EQUALS CPF
                    $onlyCpf = pathinfo($pdfName, PATHINFO_FILENAME);
                    if (strlen($onlyCpf) != 11) {
                        $onlyCpf = str_pad($onlyCpf, 11, "0", STR_PAD_LEFT);
                    }
                    $user = User::where('cpf', $onlyCpf)->first();

                    if ($user) {
                        $hashedName = storage_path('app/public/users-healthdocs/').time().rand(1,100).basename($file);
                        $response = File::move($file, $hashedName);
                        $newPublicFilepath = Storage::url('users-healthdocs/'.basename($hashedName));

                        $DBBatch[] = [
                            'user_id' => $user->id,
                            'url_doc' => $newPublicFilepath,
                            'title' => $docTitle,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];

                    } else {
                        $this->info('Arquivo não tem usuário cadastrado: '.$pdfName);
                    }
                }
                if (count($DBBatch) > 0) {
                    DB::table('healthdocs')->insert($DBBatch);
                } else {
                    $this->info('Não há registros para serem salvos');
                }
                $this->info('Arquivos Cardápios cadastrados');

                break;

            default:
                $this->info('this is not an option x-x');
                break;
        }

        return true;
    }
}
