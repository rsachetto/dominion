<?php
define('DB_SERVER', 'localhost:3036');
define('DB_USERNAME', 'adminiFj7Raf');
define('DB_PASSWORD', 'hXHhRdDKJyUj');
define('DB_DATABASE', 'dominion');
//$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
$dbh = new PDO("mysql:host=DB_SERVER;dbname=DB_DATABASE", DB_USERNAME, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>