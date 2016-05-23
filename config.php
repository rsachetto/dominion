<?php
define('DB_USERNAME', 'adminiFj7Raf');
define('DB_PASSWORD', 'hXHhRdDKJyUj');

//$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
$dbh = new PDO("mysql:host=127.0.0.1;dbname=dominion", DB_USERNAME, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>