<?php

// Unescape the string values in the JSON array
$players = stripcslashes($_POST['players']);
error_log($players);


// Decode the JSON array
$players = json_decode($players,TRUE);
error_log($players);

// now $tableData can be accessed like a PHP array

