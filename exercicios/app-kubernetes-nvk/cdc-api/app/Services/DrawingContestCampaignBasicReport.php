<?php


namespace App\Services;


use Conti\Interfaces\ContiInterface;
use App\Exports\ReportExport;
use App\DrawingContestVote;

class DrawingContestCampaignBasicReport
{
    protected $conti;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ContiInterface $conti)
    {
        $this->conti = $conti;
    }

    public function generateEntriesReport() {

        // retornar todos os votos salvos
        // campos: categoria (nome), número identificação imagem, cpf do colaborador, nome do colaborador, data do voto
        // retornar dados atualizados da api
        // filtrar usuários dos votos nos usuários da api
        // criar lista com dados dos votos e da api

        $savedVotes = DrawingContestVote::with([
            'category:id,name',
            'picture:id,subscription',
            'user:id,cpf',
        ])->get();

        dump('Número de votos: '. $savedVotes->count());
        $votosCpf = $savedVotes->pluck('user.cpf');

        $funcs = $this->conti->getFuncionarios();
        $funcsCpf = $funcs->lazy()->pluck('cpf');
        dump($funcs->count());

        $matchedUsers = $funcs->lazy()->whereIn('cpf', $votosCpf);
        $notMatchedUsers = $savedVotes->lazy()->whereNotIn('user.cpf', $funcsCpf);

        dump('Usuários encontrados na API: '. $matchedUsers->count());
        dump('Usuários não encontrados na API: '. $notMatchedUsers->count());
        dump('Não encontrados:');
        dump($notMatchedUsers->toArray());

        $matchedUsersKeyed = $matchedUsers->keyBy('cpf');

//        $votesArr = $savedVotes->toArray();
//        $respostasFilled = array_map(function ($vote) use ($matchedUsersKeyed) {
//            $userApi = $matchedUsersKeyed->get($vote['user']['cpf']);
//            dump($vote['user']['cpf'] .' - '. ($userApi->nome_funcio ?? '-'));
//
//            return [
//                'categoria_nome' => $vote['category']['name'],
//                'imagem_inscricao' => $vote['picture']['subscription'],
//                'colaborador_cpf' => $vote['user']['cpf'],
//                'colaborador_nome' => ($userApi) ? $userApi->nome_funcio : '-',
//                'enviado_em' => $vote['created_at']
//            ];
//        }, $votesArr);

//        $respostasFilled = $savedVotes->map(function ($vote) use ($matchedUsersKeyed) {
//            $userApi = $matchedUsersKeyed->get($vote->user->cpf);
//
//            return [
//                'categoria_nome' => $vote->category->name,
//                'imagem_inscricao' => $vote->picture->subscription,
//                'colaborador_cpf' => $vote->user->cpf,
//                'colaborador_nome' => ($userApi) ? $userApi->nome_funcio : '-',
//                'enviado_em' => $vote->created_at
//            ];
//        });

        $basename = $this->generateFileBasename(now());
        $headers = [
            'categoria_nome',
            'imagem_inscricao',
            'colaborador_cpf',
            'colaborador_nome',
            'enviado_em'
        ];

        $entries = [];

        foreach($savedVotes as $item) {
            $row = [];
//            $row[] = $item['categoria_nome'];
//            $row[] = $item['imagem_inscricao'];
//            $row[] = $item['colaborador_cpf'];
//            $row[] = $item['colaborador_nome'];
//            $row[] = $item['enviado_em'];
            $row[] = $item->category->name;
            $row[] = $item->picture->subscription;
            $row[] = $item->user->cpf;
            $row[] = $matchedUsersKeyed->get($item->user->cpf)->nome_funcio ?? '-';
            $row[] = $item->created_at;
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateFileBasename($date) {
        return 'reports/drawing-contest/'.$date->format('Y-m-d-His').'-basic-votes';
    }

}
