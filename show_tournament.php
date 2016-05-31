<?php
include('session.php');
include('helpers.php');

$t_id = $_GET['t_id'];
$user_id = $_GET['user_id'];

// do query
$stmt = $dbh->prepare('SELECT id, date, name, city, state FROM tournament WHERE id=:t_id');
$stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);

$stmt->execute();

$tournament_info = array();
$tournament_info = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $dbh->prepare('SELECT id, username, name FROM user WHERE id IN (SELECT user_id FROM tournament_has_user WHERE tournament_id=:t_id)');
$stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);

$stmt->execute();

$players = array();
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subscribed = false;

foreach ($players as $player) {
    if($player['id'] == $user_id) {
        $subscribed = true;
        break;
    }
}

error_log(print_r($players, true));

$num_players = count($players);

$tournament_bonus = calc_bonus($num_players);

?>

<table class="table" id="tournament-table" style="font-size: 16px;">
    <tbody>
    <tr><td colspan="3">Evento: <?php echo $tournament_info['name']." (".$tournament_info['city']." - ".$tournament_info['state'].") "; ?></td></tr>
    <tr>
        <td>Data: <?php echo date( 'd/m/y', strtotime($tournament_info['date']));?></td>
        <td>Quantidade de jogadores: <?php echo $num_players; ?></td>
        <td>Coeficiente: <?php echo $tournament_bonus; ?></td>
    </tr>
    </tbody>
</table>
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