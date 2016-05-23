<?php
include("config.php");
require("password.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $myusername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $mypassword = sha1(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    try
    {
        /*** prepare the select statement ***/
        $stmt = $dbh->prepare("SELECT id, username, role FROM user WHERE username = :myusername AND password = :mypassword");

        /*** bind the parameters ***/
        $stmt->bindParam(':myusername', $myusername, PDO::PARAM_STR);
        $stmt->bindParam(':mypassword', $mypassword, PDO::PARAM_STR);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $user_info = $stmt->fetch();
        //$user_id = $stmt->fetchColumn();
        //$role = $stmt->fetchColumn();

        /*** if we have no result then fail boat ***/
        if($user_info == false)
        {
            $message = 'Login Failed';
        }
        /*** if we do have a result, all is well ***/
        else
        {
            /*** set the session user_id variable ***/
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            $message = 'Permissao: '.var_dump($user_info);
            
            //if($role == "organizador")
            //header("location:dashboard_organizer.php");
            //elseif ($role == "jogador")
            //    header("location:dashboard_player.php");
        }


    }
    catch(Exception $e)
    {
        /*** if we are here, something has gone wrong with the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }

}
?>
<html>

<head>
    <title>Login Page</title>

    <style type = "text/css">
        body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
        }

        label {
            font-weight:bold;
            width:100px;
            font-size:14px;
        }

        .box {
            border:#666666 solid 1px;
        }
    </style>

</head>

<body bgcolor = "#FFFFFF">

<div align = "center">
    <div style = "width:300px; border: solid 1px #333333; " align = "left">
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>

        <div style = "margin:30px">

            <form action = "" method = "post">
                <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                <input type = "submit" value = " Submit "/><br />
            </form>

            <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $message; ?></div>

        </div>

    </div>

</div>

</body>
</html>