<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use \Sushi\Sushi;

    protected $schema = [
        'is_external' => 'integer',
        'is_ionicon' => 'integer',
        'needs_role' => 'integer'
    ];

    // custom icon source can be any (accessible) external svg
//    'icon_src' => 'https://unpkg.com/ionicons@5.0.0/dist/ionicons/svg/add-circle.svg',
//    'icon_src' => 'https://app.casadiconti.com.br/assets/ionicons/cipa-logo-outline.svg',
//    'icon_src' => 'http://cdc-api.test/storage/ionicons/cipa-logo-outline.svg', // local
//    'icon_src' => '/assets/ionicons/cipa-logo-outline.svg', // it also works with bundled assets
    public function getRows()
    {
        return [
            [
                'label' => 'SIPAT 2021',
                'is_external' => 1,
                'link' => 'https://casadiconti.weex.digital/',
                'is_ionicon' => 0,
                'icon_name' => null,
                'icon_src' => '/assets/ionicons/cipa-logo-outline.svg',
                'icon_color' => '#086634',
                'slot' => 'top',
                'start_date' => '2021-08-01',
                'end_date' => '2021-08-13',
                'needs_role' => false,
                'roles' => null
            ],
            [
                'label' => '13° Pintando a Casa Di Conti (T)',
                'is_external' => 0,
                'link' => '/pintando-a-casadiconti',
                'is_ionicon' => 1,
                'icon_name' => 'brush',
                'icon_src' => null,
                'icon_color' => 'red',
                'slot' => 'top',
                'start_date' => '2021-09-15',
                'end_date' => '2021-09-22',
                'needs_role' => true,
                'roles' => 'Administrador|Tester_VotacaoFotos'
            ],
            [
                'label' => '13° Pintando a Casa Di Conti',
                'is_external' => 0,
                'link' => '/pintando-a-casadiconti',
                'is_ionicon' => 1,
                'icon_name' => 'brush',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2021-09-13',
                'end_date' => '2021-09-14',
                'needs_role' => false,
                'roles' => null
            ],
            [
                'label' => '13° Pintando a Casa Di Conti (2)',
                'is_external' => 0,
                'link' => '/pintando-a-casadiconti/segunda-etapa',
                'is_ionicon' => 1,
                'icon_name' => 'brush',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2021-09-16',
                'end_date' => '2021-09-17',
                'needs_role' => true,
                'roles' => 'Comission_VotacaoFotos'
            ],
            [
                'label' => 'Cesta de Natal 2021',
                'is_external' => 0,
                'link' => '/cesta-natal',
                'is_ionicon' => 1,
                'icon_name' => 'gift',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2021-10-18',
                'end_date' => '2021-10-29',
                'needs_role' => false,
                'roles' => null
            ],
            [
                'label' => 'Campanha de Vacinação',
                'is_external' => 0,
                'link' => '/campanha-vacinacao',
                'is_ionicon' => 1,
                'icon_name' => 'reader',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2022-01-10',
                'end_date' => '2022-04-08',
                'needs_role' => false,
                'roles' => null
            ],
            [
                'label' => 'Jaqueta Burguesa',
                'is_external' => 0,
                'link' => '/jaqueta-burguesa',
                'is_ionicon' => 1,
                'icon_name' => 'reader',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2022-02-01',
                'end_date' => '2022-02-08',
                'needs_role' => false,
                'roles' => null
            ],
            [
                'label' => 'Camiseta Copa',
                'is_external' => 0,
                'link' => '/camiseta-copa',
                'is_ionicon' => 1,
                'icon_name' => 'reader',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2022-09-12',
                'end_date' => '2022-09-29',
                'needs_role' => true,
                'roles' => 'Administrador|UpdatersOrder_CamisetaCopa'
            ],
            [
                'label' => '14° Pintando a Casa Di Conti',
                'is_external' => 0,
                'link' => '/pintando-a-casadiconti',
                'is_ionicon' => 1,
                'icon_name' => 'brush',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2022-09-20',
                'end_date' => '2022-09-22',
                'needs_role' => false,
                'roles' => null
            ],
            [
                'label' => '14° Pintando a Casa Di Conti (2)',
                'is_external' => 0,
                'link' => '/pintando-a-casadiconti/segunda-etapa',
                'is_ionicon' => 1,
                'icon_name' => 'brush',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2022-09-21',
                'end_date' => '2022-09-22',
                'needs_role' => true,
                'roles' => 'Administrador|Comission_VotacaoFotos'
            ],
            [
                'label' => 'Cesta de Natal 2022',
                'is_external' => 0,
                'link' => '/cesta-natal',
                'is_ionicon' => 1,
                'icon_name' => 'gift',
                'icon_src' => null,
                'icon_color' => '#929292',
                'slot' => 'top',
                'start_date' => '2022-11-07',
                'end_date' => '2022-11-19',
                'needs_role' => true,
                'roles' => 'Usuarios_CestaNatal2022'
            ],
        ];
    }
}
