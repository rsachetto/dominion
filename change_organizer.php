<?php

include('session.php');

$user_id = $_POST['uid'];
$new_role = $_POST['new_role'];

$stmt = $dbh->prepare("UPDATE dominion.user SET role=:new_role WHERE id=:user_id");

/*** bind the parameters ***/
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindParam(':new_role', $new_role, PDO::PARAM_STR);

$success = $stmt->execute();

if($success) {
    $response_array['status'] = 'success';
}
else {
    $response_array['status'] = 'error';
}

header('Content-type: application/json');
echo json_encode($response_array);
