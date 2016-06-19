<?php
include("session.php");

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tournament';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
    array( 'db' => 'id', 'dt' => 'id'),
    array( 'db' => 'name',  'dt' => 'name' ),
    array(
        'db'        => 'date',
        'dt'        => 'date',
        'formatter' => function( $d, $row ) {
            return date( 'd/m/y', strtotime($d));
        }
    ),
    array( 'db' => 'city',     'dt' => 'city' ),
    array( 'db' => 'state',     'dt' => 'state' ),
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );


if(!isset($_POST['player_query'])) {
    if (isset($_POST['user_id']))
        $whereAll = "user_id = " . $_POST['user_id'];
    else $whereAll = "1 = 1";
    if ($_POST['submitted'] == 'false') {
        $whereAll = $whereAll . " AND submitted=0";
    } else if ($_POST['submitted'] == 'true') {
        $whereAll = $whereAll . " AND submitted=1";

        if (isset($_POST['validated']) && $_POST['validated'] == 'true') {
            $whereAll = $whereAll . " AND validated=1";
        } else {
            $whereAll = $whereAll . " AND validated=0";
        }
    }
}
else if ($_POST['player_query'] == 'open'){
    $whereAll = "tournament.submitted=0 and  tournament.date >= CURRENT_DATE and tournament.id not in (select tournament_has_user.tournament_id from tournament_has_user where user_id =".$_POST['user_id'].")";
}
else if ($_POST['player_query'] == 'subscribed') {
    $whereAll = "tournament.submitted=0 and tournament.id in (select tournament_has_user.tournament_id from tournament_has_user where user_id =".$_POST['user_id'].")";
}
else if ($_POST['player_query'] == 'validated') {
    $whereAll = "tournament.submitted=1 and tournament.validated=1 and tournament.id in (select tournament_has_user.tournament_id from tournament_has_user where user_id =".$_POST['user_id'].")";
}

error_log($whereAll);

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult = null, $whereAll )
);

