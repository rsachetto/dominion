<?php

// Unescape the string values in the JSON array
$players = stripcslashes($_POST['players']);

// Decode the JSON array
//$players = json_decode($_POST['players'],TRUE);

// now $tableData can be accessed like a PHP array
error_log($players);
