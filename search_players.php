<?php
include("config.php");
$query = $_GET['query'].'%'; // add % for LIKE query later

// do query
$stmt = $dbh->prepare('SELECT id, username, name FROM user WHERE (username LIKE :query or name LIKE :query)');
$stmt->bindParam(':query', $query, PDO::PARAM_STR);
$stmt->execute();

$results=$stmt->fetchAll(PDO::FETCH_ASSOC);

// and return to typeahead

$results_j["suggestions"] = $results;

$json=json_encode($results_j);
header('Content-Type: application/json');
echo $json;

