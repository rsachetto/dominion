<?php
include("config.php");
require("password.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $myusername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $mypassword = sha1(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    try
    {
        /*** prepare the select statement ***/
        $stmt = $dbh->prepare("SELECT id, username, role, name FROM dominion.user WHERE username = :myusername AND password = :mypassword");

        /*** bind the parameters ***/
        $stmt->bindParam(':myusername', $myusername, PDO::PARAM_STR);
        $stmt->bindParam(':mypassword', $mypassword, PDO::PARAM_STR);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $user_info = $stmt->fetch();

        /*** if we have no result then fail boat ***/
        if($user_info == false)
        {
            $message = 'Login Failed';
        }
        /*** if we do have a result, all is well ***/
        else
        {
            $user_id = $user_info['id'];
            $role = $user_info['role'];
            $name = $user_info['name'];

            /*** set the session user_id variable ***/
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $myusername;
            $_SESSION['name'] = $name;

            //$message = 'Permissao: '.$role. " Id: ".$user_id;
            
            if($role == "organizer")
                header("location:dashboard_organizer.php");
            elseif ($role == "player")
                header("location:dashboard_player.php");
            elseif ($role == "admin")
                header("location:dashboard_admin.php");
        }


    }
    catch(Exception $e)
    {
        /*** if we are here, something has gone wrong with the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }

    error_log($message);


}
?>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <link href="css/signin.css" rel="stylesheet">

    <title>Login Page</title>

</head>

<body>

<div class="container">

    <form class="form-signin" action = "" method = "post" >
        <h2 class="form-signin-heading">Login</h2>
        <label for="username" class="sr-only">Email address</label>
        <input type="text" name="username" class="form-control" placeholder="Usuário" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Senha" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>