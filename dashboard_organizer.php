<?php

//TODO: o organizador pode ser também jogador, então ele tera o mesmo menu do jogador e podera se inscrever em torneios

include('session.php');
?>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/u/bs/dt-1.10.12,r-2.1.0/datatables.min.css"/>


    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/locale/pt-br.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/locale/pt-br.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>

    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script src="js/bootstrap3-typeahead.min.js"></script>


    <script type="text/javascript" src="https://cdn.datatables.net/u/bs/dt-1.10.12,r-2.1.0/datatables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            $("#content").load("show_tournaments.php");


            $.ajaxSetup ({
                // Disable caching of AJAX responses
                cache: false
            });

            $("#overview").click(function(event){
                $("#content").load("show_tournaments.php");
            });

            $("#add-champ").click(function(event){
                $("#content").load("add_tournament_form.php");
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
            <div class="profile-content" id="content">
            </div>
        </div>
    </div>
</div>

</body>

</html>