<?php
include('config.php');
session_start();

if(!isset($_SESSION['user_id'])){
    header("location:login.php");
}

else {

    try
    {

        /*** prepare the insert ***/
        $stmt = $dbh->prepare("SELECT username FROM user WHERE user_id = :user_id");

        /*** bind the parameters ***/
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $phpro_username = $stmt->fetchColumn();

        /*** if we have no something is wrong ***/
        if($phpro_username == false)
        {
            header("location:login.php");
        }
        else
        {
            header("location:dashboard.php");
        }
    }
    catch (Exception $e)
    {
        /*** if we are here, something is wrong in the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }

}
?>