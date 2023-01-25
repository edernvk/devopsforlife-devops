<?php

namespace App\Services;

use App\DrawingContestVote;
use App\Exports\ReportExport;

class DrawingContestCampaignVotesReport
{
    public function generateEntriesReport() {

        // retornar todos os votos salvos
        // campos: categoria (nome), número identificação imagem, número de votos da imagem
        // criar lista com dados dos votos

        $countedVotes = DrawingContestVote::with([
                'category:id,name',
                'picture:id,subscription'
            ])
            ->groupBy(['category_id', 'picture_id'])
            ->selectRaw('category_id, picture_id, count(picture_id) as votes')
            ->orderBy('category_id')
            ->orderByDesc('votes')
            ->get();

        dump('Número de votos: '. $countedVotes->count());

        $basename = $this->generateFileBasename(now());
        $headers = [
            'categoria_nome',
            'imagem_inscricao',
            'qtd_votos'
        ];

        $entries = [];

        foreach($countedVotes as $item) {
            $row = [];
//            $row[] = $item['categoria_nome'];
//            $row[] = $item['imagem_inscricao'];
//            $row[] = $item['colaborador_cpf'];
//            $row[] = $item['colaborador_nome'];
//            $row[] = $item['enviado_em'];
            $row[] = $item->category->name;
            $row[] = $item->picture->subscription;
            $row[] = $item->votes;
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateFileBasename($date) {
        return 'reports/drawing-contest/'.$date->format('Y-m-d-His').'-votes-count';
    }
}
