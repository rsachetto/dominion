<?php
include('session.php');
?>
<html">

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Bem vindo jogador </title>
</head>

<body>
<?php
include('player_sidebar.php');
?>
<h1>Welcome <?php echo $_SESSION['username']; ?></h1>
<h2><a href = "logout.php">Sign Out</a></h2>
</body>

</html>