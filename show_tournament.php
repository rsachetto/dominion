<?php
include('session.php');
include('helpers.php');

$t_id = $_GET['t_id'];
$user_id = $_GET['user_id'];

$tournament_info = get_tournament_info($dbh, $t_id);
$players = get_tournament_players($dbh, $t_id);

$subscribed = false;

foreach ($players as $player) {
    if($player['id'] == $user_id) {
        $subscribed = true;
        break;
    }
}

$num_players = count($players);

$tournament_info['bonus'] = calc_bonus($num_players);
?>
<?php include 'tournament_header.php'; ?>
<table class="table table-striped" id="players-table">
    <thead>
    <tr>
        <th>Nome</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($players as $player) {
        $tr_name = '<td>'.$player['name'].'</td>';
        echo '<tr>'.$tr_name.'</tr>';
    }
    ?>
    </tbody>
</table>

<?php

if(!$subscribed) echo '<button class="btn btn-primary" id="btn-subscribe">Inscrever</button>';
else echo '<button class="btn btn-danger" id="btn-cancel">Cancelar inscrição</button>';
?>

<script type="text/javascript">

    jQuery(document).ready(function() {
        $('#btn-subscribe').click(function (event) {

            event.preventDefault();

            var tId = <?php echo $t_id; ?>;
            var uId = <?php echo $user_id; ?>;
            $.ajax({
                type: "GET",
                url: "subscribe_user.php",
                data: "t_id="+tId+"&user_id="+uId+"&subscribe=1",
                success: function(data) {
                    if(data.status == 'success'){
                        alert("Inscrito com sucesso!");
                        $("#content").load("show_tournament.php?t_id="+tId+"&user_id="+uId);
                    }else if(data.status == 'error'){
                        alert("Erro ao realizar inscrição!");
                    }
                }
            });

        });

        $('#btn-cancel').click(function (event) {

            event.preventDefault();

            var tId = <?php echo $t_id; ?>;
            var uId = <?php echo $user_id; ?>;
            $.ajax({
                type: "GET",
                url: "subscribe_user.php",
                data: "t_id="+tId+"&user_id="+uId+"&subscribe=0",
                success: function(data) {
                    if(data.status == 'success'){
                        alert("Inscrição cancelada com sucesso!");
                        $("#content").load("show_tournament.php?t_id="+tId+"&user_id="+uId);
                    }else if(data.status == 'error'){
                        alert("Erro ao cancelar inscrição!");
                    }
                }
            });

        });

    });

</script>