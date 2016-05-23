<?php
include("config.php");
$query = $_GET['s'].'%'; // add % for LIKE query later

// do query
$stmt = $dbh->prepare('SELECT username FROM user WHERE username LIKE = ?');
//$stmt->bindParam(':query', $query, PDO::PARAM_STR);
$stmt->execute(array($query));

// populate results
$results = array();
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $row) {
    $results[] = $row;
}

// and return to typeahead
return json_encode($results);