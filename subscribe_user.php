<?php
include "session.php";
include "helpers.php";

$success = true;
$t_id = $_GET['t_id'];
$user_id = $_GET['user_id'];

if(isset($_GET['subscribe'])) {
    if($_GET['subscribe'] == 1) {
        $stmt = $dbh->prepare('INSERT into dominion.tournament_has_user (tournament_id, user_id) VALUE (:t_id, :u_id)');
    }
    else if($_GET['subscribe'] == 0) {
        $stmt = $dbh->prepare('DELETE from dominion.tournament_has_user WHERE tournament_id=:t_id and user_id=:u_id');
    }

    $stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);
    $stmt->bindParam(':u_id', $user_id, PDO::PARAM_INT);
    $success &= $stmt->execute();
}

if ($success) {
    $response_array['status'] = 'success';
} else {
    $response_array['status'] = 'error';
}

header('Content-type: application/json');
echo json_encode($response_array);