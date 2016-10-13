<?
ob_start(); // ensures anything dumped out will be caught

// do stuff here
$url = 'login.php'; // this can be set based on whatever

// clear out the output buffer
while (ob_get_status())
{
    ob_end_clean();
}

// no redirect
header( "Location: $url" );
?>