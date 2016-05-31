<?php

function cmp_rank($a1, $b1)
{
    $a = floatval($a1['total']);
    $b = floatval($b1['total']);

    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}


include 'session.php';

//{"draw":1,"recordsTotal":4,"recordsFiltered":4,"data":[{"id":"8","name":"Teste2","date":"26\/05\/16","city":"Alvar\u00e3es","state":"Amazonas"}]}

$stmt = $dbh->prepare('select * from dominion.ranking');
$stmt->execute();

$tournament_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//error_log(print_r($tournament_results, true));

$prk_count = array();
$prk_user = array();

$filter= false;

if ( isset($_POST['search']) && $_POST['search']['value'] != '' ) {

    $estadosBrasileiros = array(
        'AC'=>'Acre',
        'AL'=>'Alagoas',
        'AP'=>'Amapá',
        'AM'=>'Amazonas',
        'BA'=>'Bahia',
        'CE'=>'Ceará',
        'DF'=>'Distrito Federal',
        'ES'=>'Espírito Santo',
        'GO'=>'Goiás',
        'MA'=>'Maranhão',
        'MT'=>'Mato Grosso',
        'MS'=>'Mato Grosso do Sul',
        'MG'=>'Minas Gerais',
        'PA'=>'Pará',
        'PB'=>'Paraíba',
        'PR'=>'Paraná',
        'PE'=>'Pernambuco',
        'PI'=>'Piauí',
        'RJ'=>'Rio de Janeiro',
        'RN'=>'Rio Grande do Norte',
        'RS'=>'Rio Grande do Sul',
        'RO'=>'Rondônia',
        'RR'=>'Roraima',
        'SC'=>'Santa Catarina',
        'SP'=>'São Paulo',
        'SE'=>'Sergipe',
        'TO'=>'Tocantins'
    );

    $str = $_POST['search']['value'];

    $estado = $estadosBrasileiros[$str];

    $filter = true;


}

foreach ($tournament_results as $result) {

    $id = $result['name'];
    if (!isset($prk_count[$id])) {
        $prk_count[$id] = 1;
        $prk_user[$id]['name'] = $id;
    }


    if(intval($prk_count[$id]) <= 8) {
        if(!$filter) {
            $prk_label = 'prk' . $prk_count[$id];
            $prk_user[$id][$prk_label] = $result['prk'];
            $prk_count[$id] += 1;
        }
        else {
            error_log($result['state'].' - '.$estado);
            if($result['state'] == $estado) {
                $prk_label = 'prk' . $prk_count[$id];
                error_log($prk_label.' - '.$result['prk']);
                $prk_user[$id][$prk_label] = $result['prk'];
                $prk_count[$id] += 1;
            }
        }
    }

}

$out = array();
foreach ($prk_user as &$prks) {

    //error_log(print_r($prks,true));
    $total = 0;
    for($i=1; $i <=8; $i++) {
        $prk_label = 'prk' . $i;

        if(!isset($prks[$prk_label])) {
            $prks[$prk_label] = 0;
        }

        $total += intval($prks[$prk_label]);
        $prks['total'] = $total;
    }

    $out[] = $prks;

}

//ordena s
usort($out, "cmp_rank");
$count = 1;

foreach ($out as &$prks) {
    $prks['position'] = $count;
    $count++;
}


//TODO: tem que ver a melhor forma de retornar esse resultados
$recordsTotal = count($out);
$recordsFiltered = $recordsTotal;

$ret = array(
    "draw"            => isset ( $_POST['draw'] ) ?
        intval( $_POST['draw'] ) :
        0,
    "recordsTotal"    => intval( $recordsTotal ),
    "recordsFiltered" => intval( $recordsFiltered ),
    "data"            => $out
);

echo json_encode($ret);
