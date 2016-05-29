<?php
include "session.php";
include "helpers.php";

//TODO: verificar se algum campeao ja foi adicionado
$t_id = $_POST['tId'];
$response_array['status'] = 'error';

$players = stripcslashes($_POST['players']);

// Decode the JSON array
$players = json_decode($players, TRUE);
//error_log(print_r($players, true));

if(isset($_POST['action']) && $_POST['action'] == 'validate') {
    //Primeiro validamos o torneio
    $stmt = $dbh->prepare("UPDATE tournament SET validated = 1, bonus=:tBonus WHERE id=:tournament_id");
    $stmt->bindParam(':tournament_id', $t_id, PDO::PARAM_INT);
    $stmt->bindParam(':tBonus', $_POST['tBonus'], PDO::PARAM_STR);
    $success = $stmt->execute();

    //Depois podemos atualizar o rank
    $success &= update_prk($dbh, $players, $t_id, floatval($_POST['tBonus']));
    
    if ($success) {
        $response_array['status'] = 'success';
    } else {
        $response_array['status'] = 'error';
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'invalidate') {
    $stmt = $dbh->prepare("UPDATE tournament SET validated = 0, bonus=NULL WHERE id=:tournament_id");
    $stmt->bindParam(':tournament_id', $t_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if ($success) {
        $response_array['status'] = 'success';
    } else {
        $response_array['status'] = 'error';
    }
}

else {

    $success = true;


    foreach ($players as $p) {
        $stmt = $dbh->prepare("UPDATE tournament_has_user SET num_first_places=:num_fisrt,  num_second_places=:num_second, champion=:champion, finalist=:finalist, semi_finalist=:semi_finalist WHERE user_id=:user_id AND tournament_id=:tournament_id");
        $stmt->bindParam(':tournament_id', $t_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $p["id"], PDO::PARAM_INT);
        $stmt->bindParam(':num_fisrt', $p["num_first_places"], PDO::PARAM_INT);
        $stmt->bindParam(':num_second', $p["num_second_places"], PDO::PARAM_INT);
        $stmt->bindParam(':finalist', $p["finalist"], PDO::PARAM_INT);
        $stmt->bindParam(':semi_finalist', $p["semi_finalist"], PDO::PARAM_INT);
        $stmt->bindParam(':champion', $p["champion"], PDO::PARAM_INT);
        $success &= $stmt->execute();
    }

    if ($success) {
        $stmt = $dbh->prepare("UPDATE tournament SET submitted=1 WHERE id=:tournament_id");
        $stmt->bindParam(':tournament_id', $t_id, PDO::PARAM_INT);
        $stmt->execute();
        $response_array['status'] = 'success';
    } else {
        $response_array['status'] = 'error';
    }
}

header('Content-type: application/json');
echo json_encode($response_array);
