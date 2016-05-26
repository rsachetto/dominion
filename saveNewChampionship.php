<?php

// Unescape the string values in the JSON array
$players = stripcslashes($_POST['players']);

// Decode the JSON array
$players = json_decode($players,TRUE);

switch (json_last_error()) {
    case JSON_ERROR_NONE:
        error_log(' - No errors');
        break;
    case JSON_ERROR_DEPTH:
        error_log('- Maximum stack depth exceeded');
        break;
    case JSON_ERROR_STATE_MISMATCH:
        error_log( ' - Underflow or the modes mismatch');
        break;
    case JSON_ERROR_CTRL_CHAR:
        error_log( ' - Unexpected control character found');
        break;
    case JSON_ERROR_SYNTAX:
        error_log( ' - Syntax error, malformed JSON');
        break;
    case JSON_ERROR_UTF8:
        error_log( ' - Malformed UTF-8 characters, possibly incorrectly encoded');
        break;
    default:
        error_log( ' - Unknown error');
        break;
}


// now $tableData can be accessed like a PHP array
error_log($players);
