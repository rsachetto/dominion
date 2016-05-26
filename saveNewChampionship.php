<?php
include("config.php");

// Unescape the string values in the JSON array
$players = stripcslashes($_POST['players']);

// Decode the JSON array
$players = json_decode($players,TRUE);

$cName = stripcslashes($_POST['cName']);
$cDate = $_POST['cDate'];
$cState = $_POST['cState'];
$cCity = $_POST['cCity'];
$ownerId = stripcslashes($_POST['ownerId']);


/*** prepare the insert ***/
$stmt = $dbh->prepare("INSERT INTO tournament (name, date, user_id, state, city ) VALUES (:name, :date, :user_id, :state, :city)");

/*** bind the parameters ***/
$stmt->bindParam(':name', $cName, PDO::PARAM_STR);
$stmt->bindParam(':date', $cDate, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $ownerId, PDO::PARAM_INT);
$stmt->bindParam(':state', $cState, PDO::PARAM_STR);
$stmt->bindParam(':city', $cCity, PDO::PARAM_STR);

/*** execute the prepared statement ***/
$success = $stmt->execute();
$champId = $dbh->lastInsertId();


foreach ($players as $p) {
    $stmt = $dbh->prepare("INSERT INTO tournament_has_user (tournament_id, user_id) VALUES (:tournament_id, :user_id)");
    $stmt->bindParam(':tournament_id', $champId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $p["userId"], PDO::PARAM_INT);
    $success &= $stmt->execute();
}

if($success) {
    $response_array['status'] = 'success';
}
else {
    $response_array['status'] = 'error';
}

header('Content-type: application/json');
echo json_encode($response_array);



