<?php
include("config.php");
$query = $_GET['query'].'%'; // add % for LIKE query later

// do query
$stmt = $dbh->prepare('SELECT username FROM user WHERE username LIKE :query');
$stmt->bindParam(':query', $query, PDO::PARAM_STR);
$stmt->execute();

// populate results
$results = array();
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $row) {
    $results['username'] = $row;
}
//$results=$stmt->fetchAll(PDO::FETCH_ASSOC);

// and return to typeahead

$results_j["query"] = "Unity";
$results_j["suggestions"] = $results;

$json=json_encode($results_j);
error_log($json);

return $json;