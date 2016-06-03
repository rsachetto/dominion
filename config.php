<?php

if (getenv('OPENSHIFT_MYSQL_DB_HOST')) {
    define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
    define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
    define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
    define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
    define('DB_NAME', getenv('OPENSHIFT_GEAR_NAME'));
}
else {
    define('DB_HOST', 'localhost');
    define('DB_PORT', 3306);
    define('DB_USER', 'adminiFj7Raf' );
    define('DB_PASS', 'hXHhRdDKJyUj');
    define('DB_NAME', 'dominion');
}

// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db'   => DB_NAME,
    'host' => DB_HOST
);

$attrs = array(PDO::ATTR_PERSISTENT => true);
$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
$dbh = new PDO($dsn, DB_USER, DB_PASS, $attrs);

//$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
//$dbh = new PDO("mysql:host="+127.0.0.1;dbname=dominion", DB_USERNAME, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
