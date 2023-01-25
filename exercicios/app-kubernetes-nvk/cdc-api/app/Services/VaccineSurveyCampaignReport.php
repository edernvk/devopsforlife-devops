<?php


namespace App\Services;


use App\Exports\ReportExport;
use App\VaccineSurveyCampaign;
use Conti\Interfaces\ContiInterface;

class VaccineSurveyCampaignReport
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

//        DB::enableQueryLog(); // Enable query log

        // retornar todos as respostas salvas
        // retornar dados atualizados da api
        // filtrar usuários das respostas nos usuários da api
        // criar lista com dados das respostas e da api

//        $pedidosNotApproved = VaccineSurveyCampaign::with('user')->whereHas('user', function($query) {
//            return $query->whereNull('approved');
//        })->get();
//        dump('Número de respostas de usuários desativados: '. $pedidosNotApproved->count());

        $respostasApproved = VaccineSurveyCampaign::with('user')->whereHas('user', function($query) {
            return $query->withoutGhosts()->whereNotNull('approved');
        })->get();
        dump('Número de respostas: '. $respostasApproved->count());
        $respostasCpf = $respostasApproved->pluck('user.cpf');

        $funcs = $this->conti->getFuncionarios();
        $funcsCpf = $funcs->lazy()->pluck('cpf');
        dump($funcs->count());

        $matchedUsers = $funcs->lazy()->whereIn('cpf', $respostasCpf);
        $notMatchedUsers = $respostasApproved->lazy()->whereNotIn('user.cpf', $funcsCpf);

        dump('Usuários encontrados na API: '. $matchedUsers->count());
        dump('Usuários não encontrados na API: '. $notMatchedUsers->count());
        dump('Não encontrados:');
        dump($notMatchedUsers->toArray());

        $respostasFilled = $respostasApproved->map(function ($resposta) use ($matchedUsers) {
            $userApi = $matchedUsers->firstWhere('cpf', $resposta->user->cpf);

            return [
                'user_id' => $resposta->user_id,
                'data_nascto' => ($userApi) ? $userApi->data_nascto : '-',
                'nome_funcio' => ($userApi) ? $userApi->nome_funcio : '-',
                'cronograma_idade' => $this->enumTranslate($resposta->local_age_reached),
                'primeira_dose' => $this->enumTranslate($resposta->first_dose),
                'segunda_dose' => $this->enumTranslate($resposta->second_dose),
                'enviado_em' => $resposta->created_at
            ];
        });

        $basename = $this->generateFileBasename(now());
        $headers = [
            'user_id',
            'data_nascto',
            'nome_funcio',
            'cronograma_idade',
            'primeira_dose',
            'segunda_dose',
            'enviado_em'
        ];

        $entries = [];

        foreach($respostasFilled as $item) {
            $row = [];
            $row[] = $item['user_id'];
            $row[] = $item['data_nascto'];
            $row[] = $item['nome_funcio'];
            $row[] = $item['cronograma_idade'];
            $row[] = $item['primeira_dose'];
            $row[] = $item['segunda_dose'];
            $row[] = $item['enviado_em'];
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateFileBasename($date) {
        return 'reports/vaccine-survey/'.$date->format('Y-m-d-His').'-entries';
    }

    private function enumTranslate($value) {
        switch ($value) {
            case 'yes':
                return 'SIM';
                break;
            case 'no':
                return 'NÃO';
                break;
            case 'n/a':
                return 'N/A';
                break;
            default:
                return '-';
                break;
        }
    }

}
