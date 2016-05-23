<?php
include('session.php');
?>
<html">

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#add-champ").click(function(event){
                $("#profile-content").load("add_championship_form.html");
            });
        });
    </script>

    <title>Bem vindo organizador </title>
</head>

<body>

<div class="container">
    <div class="row profile">
        <?php
        include('organizer_sidebar.php');
        ?>
        <div class="col-md-9">
            <div class="profile-content">
                Some user related content goes here...
            </div>
        </div>
    </div>
</div>

<!--<h1>Welcome --><?php //echo $_SESSION['username']; ?><!--</h1>-->
<!--<h2><a href = "logout.php">Sign Out</a></h2>-->
</body>

</html>