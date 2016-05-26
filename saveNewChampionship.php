<?php
include("config.php");

// Unescape the string values in the JSON array
$players = stripcslashes($_POST['players']);

// Decode the JSON array
$players = json_decode($players,TRUE);

$cName = stripcslashes($_POST['cName']);
$cDate = stripcslashes($_POST['cDate']);
$ownerId = stripcslashes($_POST['ownerId']);


/*** prepare the insert ***/
$stmt = $dbh->prepare("INSERT INTO tournament (name, date, user_id ) VALUES (:name, :date, :user_id )");

/*** bind the parameters ***/
$stmt->bindParam(':name', $cName, PDO::PARAM_STR);
$stmt->bindParam(':date', $cDate, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $ownerId, PDO::PARAM_INT);


/*** execute the prepared statement ***/
$stmt->execute();



