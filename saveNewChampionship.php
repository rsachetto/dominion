<?php

// Unescape the string values in the JSON array
$players = stripcslashes($_POST['players']);
error_log($players);


// Decode the JSON array
$players = json_decode($players,TRUE);
if($players == NULL) {
    error_log("NULLLLL" + json_last_error_msg());
}
else {
    error_log("NOT NULL");
}
// now $tableData can be accessed like a PHP array

