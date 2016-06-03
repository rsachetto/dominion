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
$prk_count = array();
$prk_user = array();

//TODO: aqui na verdade eu tenho que mudar o select e usar somente o estado do jogador
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
    $stmt = $dbh->prepare('select * from dominion.ranking WHERE ranking.state=:state');
    $stmt->bindParam(':state', $estado, PDO::PARAM_STR);
}
else {
    $stmt = $dbh->prepare('select * from dominion.ranking');
}

$stmt->execute();

$tournament_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tournament_results as $result) {

    $id = $result['name'];
    if (!isset($prk_count[$id])) {
        $prk_count[$id] = 1;
        $prk_user[$id]['name'] = $id;
    }


    if(intval($prk_count[$id]) <= 8) {
        $prk_label = 'prk' . $prk_count[$id];
        $prk_user[$id][$prk_label] = $result['prk'];
        $prk_count[$id] += 1;
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
