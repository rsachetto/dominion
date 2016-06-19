<?php

include('session.php');
include('helpers.php');
require("password.php");

$user_id = $_SESSION['user_id'];

$mypassword = sha1(filter_var($_POST['current_password'], FILTER_SANITIZE_STRING));
$newpassword = sha1(filter_var($_POST['new_password'], FILTER_SANITIZE_STRING));
$newpassword2 = sha1(filter_var($_POST['new_password2'], FILTER_SANITIZE_STRING));

$message='';

if($newpassword != $newpassword2) {
    $message = 'Senhas não conferem!';
}
else {
    $stmt = $dbh->prepare("SELECT username FROM dominion.user WHERE id = :user_id AND password = :mypassword");

    /*** bind the parameters ***/
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam(':mypassword', $mypassword, PDO::PARAM_STR);

    $stmt->execute();

    /*** check for a result ***/
    $user_info = $stmt->fetch();


    /*** if we have no result then fail boat ***/
    if($user_info == false)
    {
        $message = 'Senha atual não confere';
    }
    /*** if we do have a result, all is well ***/
    else
    {
        $stmt = $dbh->prepare("UPDATE dominion.user SET password=:password WHERE id=:user_id");

        /*** bind the parameters ***/
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindParam(':password', $newpassword, PDO::PARAM_STR);

        $stmt->execute();
    }

}

if(empty($message)) {
    $response_array['status'] = 'success';
}
else {
    $response_array['status'] = $message;
}

header('Content-type: application/json');
echo json_encode($response_array);
