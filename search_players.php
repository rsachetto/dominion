<?php
include("config.php");
$query = $_GET['query'].'%'; // add % for LIKE query later

// do query
$stmt = $dbh->prepare('SELECT username FROM user WHERE username LIKE :query');
$stmt->bindParam(':query', $query, PDO::PARAM_STR);
$stmt->execute();

// populate results
//$results = array();
//foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $row) {
//    $results['username'] = $row;
//}
$results=$statement->fetchAll(PDO::FETCH_ASSOC);
$json=json_encode($results);
error_log($json);
// and return to typeahead
return json_encode($json);