<?php
include('session.php');
?>
<html">

<head>
    <title>Bem vindo jogador </title>
</head>

<body>
<h1>Welcome <?php echo $_SESSION['username']; ?></h1>
<h2><a href = "logout.php">Sign Out</a></h2>
</body>

</html>