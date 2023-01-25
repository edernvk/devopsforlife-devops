<?php

namespace App\Console\Commands;

use App\Videocast;
use Illuminate\Console\Command;

class ManageVideocasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videocasts {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add/delete videocasts (conti-cast, etc)';

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

//        Docs: videocast object format
//        [
//            "id" => integer (optional),
//            "title" => string,
//            "description" => string,
//            "video_url" => VIMEO_URL,
//            "date" => DD/MM/AAAA - HH:MM hrs
//        ]

        // action to be performed [ ADD / REMOVE / LIST ]
        $action = $this->argument('action');

        switch($action) {
            case "add":
                $this->info('Cadastrando novo videocast');

                $fields = ["title", "description", "video_url", "date"];
                $vid = collect([]);

                foreach($fields as $field) {
                    $value = $this->ask($field);
                    $vid->put($field, $value);
                }

                $this->info($vid);

                if ($this->confirm('Cadastrar videocast?')) {

                    $comparer = isset($vid['id']) ? ['id' => $vid['id']] : ['video_url' => $vid['video_url']];
                    if ($video = Videocast::updateOrCreate($comparer, $vid->toArray())) {
                        $this->info($video);
                        $this->info('Novo videocast cadastrado com sucesso!');
                    } else {
                        $this->erro('Falha ao cadastrar novo videocast...');
                    }

                } else {
                    $this->info('Ação encerrada sem mudanças');
                }
                break;

            case "delete":
                $this->info('Excluindo videocast');

                $vids = Videocast::all();

                if ($vids->count() === 0) {
                    $this->info('Não há nenhum videocast cadastrado.');
                    return true;
                }

                $pick = $this->choice('Escolha o videocast a ser excluído', $vids->pluck('title')->toArray());

                $this->info('Videocast selecionado:');
                $selected = Videocast::where('title', $pick)->first();
                $this->info($selected);

                if ($this->confirm('Excluir videocast?')) {
                    if ($selected->delete()) {
                        $this->info('Videocast excluído com sucesso!');
                    } else {
                        $this->erro('Falha ao excluir videocast...');
                    }
                } else {
                    $this->info('Ação encerrada sem mudanças');
                }

                break;

            case "list":
                $this->info('Listando videocasts');
                $headers = ["id", "title", "description", "video_url", "date"];
                $videos = Videocast::all($headers);
                foreach($videos as $vid) {
                    $this->displayEloquentModel($headers, $vid);
                }
                // $this->table($headers, $videos);

                break;

            default:
                $this->info('Ação inválida!');
                break;
        }

        return true;
    }

    private function displayEloquentModel($headers, $obj) {
        foreach($headers as $key) {
            $this->info("| " . strtoupper($key) . " => " . $obj->$key);
        }
        $this->line('+--------------------+');
    }
}
