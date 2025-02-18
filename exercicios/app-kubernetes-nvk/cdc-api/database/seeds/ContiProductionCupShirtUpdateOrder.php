<?php

use Illuminate\Database\Seeder;

class ContiProductionCupShirtUpdateOrder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cupshirtUpdateOrderRole = \App\Role::query()->updateOrCreate([
            'name' => 'UpdatersOrder_CamisetaCopa'],[
            'description' => 'Usuarios com erro em seu pedio na camiseta da copa do mundo'
        ]);

        $users = \App\User::query()->whereIn('cpf', [
            '99999999991',
            "01528479874",
            "01537457896",
            "03007142970",
            "03359985850",
            "03711176909",
            "05287398913",
            "05842532816",
            "05903931979",
            "07520448347",
            "07788366579",
            "07889326831",
            "07889553811",
            "08048953927",
            "08128148940",
            "09170290970",
            "09575230922",
            "09588157986",
            "09618639860",
            "10148718914",
            "10593925939",
            "10642941467",
            "11073337847",
            "11965877877",
            "13087265896",
            "13712104898",
            "13802289641",
            "13825075850",
            "14392947637",
            "14508158986",
            "14573405852",
            "15878862867",
            "15880023818",
            "16461108890",
            "17028336824",
            "17047182870",
            "17186342850",
            "19097349850",
            "20085864803",
            "21576983889",
            "21720923809",
            "21949536807",
            "22200616805",
            "22349564800",
            "22490750852",
            "22839229846",
            "23556631814",
            "24228004830",
            "24725719846",
            "24813506852",
            "25119103812",
            "25235280881",
            "25932425822",
            "26052700840",
            "26380151800",
            "26518785888",
            "26573505859",
            "27067390885",
            "27231722873",
            "27399441871",
            "27429560857",
            "27492803893",
            "27673755823",
            "27729751852",
            "27776598852",
            "27812881873",
            "28528902838",
            "28714244896",
            "28850875878",
            "28923131806",
            "29083189899",
            "29192259871",
            "29386344807",
            "29418269805",
            "29429950843",
            "29525946860",
            "29661916861",
            "29772134810",
            "30044395817",
            "30122151828",
            "30150656882",
            "30225601842",
            "30240692837",
            "30258821825",
            "30267247850",
            "30684819880",
            "30896403840",
            "31011970821",
            "31075068851",
            "31447224809",
            "31676357890",
            "31876678801",
            "32215161850",
            "32221578805",
            "32572326833",
            "32600877886",
            "32733567829",
            "32828539806",
            "32936603803",
            "33135970841",
            "33392850802",
            "33498302850",
            "33556333805",
            "33578466832",
            "33579137808",
            "33924416869",
            "34141369888",
            "34172991874",
            "34390720880",
            "34429046840",
            "34632002879",
            "34647786811",
            "34907021828",
            "34953419863",
            "35035210898",
            "35252061866",
            "35292933856",
            "35334988831",
            "35508407804",
            "35539015860",
            "35605047833",
            "35698028842",
            "35777217826",
            "35956737867",
            "36358466860",
            "36418009860",
            "36427579878",
            "36578787852",
            "36653130827",
            "36736985838",
            "36800689807",
            "36829724805",
            "36974248822",
            "37110538826",
            "37165541896",
            "37318704877",
            "37611280880",
            "37729088886",
            "37801034864",
            "37804358881",
            "37912959858",
            "38069641819",
            "38073335840",
            "38144160827",
            "38355238800",
            "38506112800",
            "38541417859",
            "38937163861",
            "39011214803",
            "39015542899",
            "39017546875",
            "39242055824",
            "39251162816",
            "39424253869",
            "39555568839",
            "39579487898",
            "39604923838",
            "39848262814",
            "39926936865",
            "39934627850",
            "39991947825",
            "40138377812",
            "40269721860",
            "40323006825",
            "40444525807",
            "40506273881",
            "40833161873",
            "40857243802",
            "40896780805",
            "41071898809",
            "41104599821",
            "41139328832",
            "41172643890",
            "41198302844",
            "41518231829",
            "41525246852",
            "41568453892",
            "41780231873",
            "41812872895",
            "42030434841",
            "42072274826",
            "42082627888",
            "42292452898",
            "42320395890",
            "42360543881",
            "42452512800",
            "42580507841",
            "42625640880",
            "42653730898",
            "42886096822",
            "42901523846",
            "43124831837",
            "43264037807",
            "43335204875",
            "43368354892",
            "43430540801",
            "43525582811",
            "43631372825",
            "44330653826",
            "44487710839",
            "44586667893",
            "44642450807",
            "44713895806",
            "44745754825",
            "44766786882",
            "44800587883",
            "44812548829",
            "44844650866",
            "44877718893",
            "45111829814",
            "45125795836",
            "45513817802",
            "45561342851",
            "45610822807",
            "45649994862",
            "45706855870",
            "45849489843",
            "45883522882",
            "46047043844",
            "46422438877",
            "46470376819",
            "46668491812",
            "46744023870",
            "46791010835",
            "46911331862",
            "47173015871",
            "47324575846",
            "47329249807",
            "47491692850",
            "47506236877",
            "47802899800",
            "47962565802",
            "47980276850",
            "48051852877",
            "48073484854",
            "48509971803",
            "48513339865",
            "48690042830",
            "48834421876",
            "49006516805",
            "49052164860",
            "49059123859",
            "49308062831",
            "49351238857",
            "49694417821",
            "49746026836",
            "50278278817",
            "50453842879",
            "50774804866",
            "51217335854",
            "52022133870",
            "52214363882",
            "52546295845",
            "52927615837",
            "53792427826",
            "53921642850",
            "54412935191",
            "54990953886",
            "55823226802",
            "76968200953",
            "79278574872",
            "79559727915",
            "85892912868",
            "95750312100",
        ])->get();

        $cupshirtUpdateOrderRole->users()->sync($users);
    }
}
