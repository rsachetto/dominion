<?php

include('session.php');
include('helpers.php');

$t_id = $_GET['t_id'];

// do query
$stmt = $dbh->prepare('SELECT id, user_id, date, name, city, state, bonus FROM tournament WHERE id=:t_id');
$stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);

$stmt->execute();

$tournament_info = array();
$tournament_info = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $dbh->prepare('select user.id, user.username, user.name, tournament_has_user.num_first_places, tournament_has_user.num_second_places, tournament_has_user.champion, tournament_has_user.finalist, tournament_has_user.semi_finalist
                       from user
                          join tournament_has_user on (user.id = tournament_has_user.user_id)
                          join tournament on (tournament_has_user.tournament_id = :t_id)
                          GROUP BY user.id ORDER  BY tournament_has_user.champion DESC, tournament_has_user.num_first_places DESC');

$stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);

$stmt->execute();

$players = array();
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
$num_players = count($players);

$num_players = count($players);

if(isset($_GET['validating']))
    $tournament_info['bonus'] = calc_bonus($num_players);

?>

<h3>Resultados: </h3>

<table class="table" id="tournament-table" style="font-size: 16px;">
    <tbody>
    <tr><td colspan="3">Evento: <?php echo $tournament_info['name']." (".$tournament_info['city']." - ".$tournament_info['state'].") "; ?></td></tr>
    <tr>
        <td>Data: <?php echo date( 'd/m/y', strtotime($tournament_info['date']));?></td>
        <td>Quantidade de jogadores: <?php echo $num_players; ?></td>
        <td>Coeficiente: <?php echo $tournament_info['bonus']; ?></td>
    </tr>
    </tbody>
</table>
<table class="table table-striped" id="players-table">
    <thead>
    <tr>
        <th>Nome</th>
        <th>1 Colocaçao</th>
        <th>2 Colocaçao</th>
        <th>Semi</th>
        <th>Finalista</th>
        <th>Campeão</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($players as $player) {
        $tr_name = '<td>'.$player['name'].'</td>';
        $tr_num_first = '<td>'.$player['num_first_places'].'</td>';
        $tr_num_second = '<td>'.$player['num_second_places'].'</td>';

        $icon = 'glyphicon-remove';
        if($player['semi_finalist'] == 1) {
            $icon = 'glyphicon-ok';
        }
        $tr_semi = '<td><span class="glyphicon '.$icon.'"></span></a></td>';

        $icon = 'glyphicon-remove';
        if($player['finalist'] == 1) {
            $icon = 'glyphicon-ok';
        }
        $tr_finalist = '<td><span class="glyphicon '.$icon.'"></span></a></td>';

        $icon = 'glyphicon-remove';
        if($player['champion'] == 1) {
            $icon = 'glyphicon-ok';
        }
        $tr_champ = '<td><span class="glyphicon '.$icon.'"></span></a></td>';
        echo '<tr>'.$tr_name.$tr_num_first.$tr_num_second.$tr_semi.$tr_finalist.$tr_champ.'</tr>';
    }
    ?>
    </tbody>
</table>

<?php
if(isset($_GET['validating'])) {
    echo '<button class="btn btn-primary" id = "btn-save" >Validar</button >';
}
else if(isset($_GET['valid'])) {
    echo '<button class="btn btn-danger" id = "btn-cancel" >Invalidar</button >';
}
?>
<script type="text/javascript">

    jQuery(document).ready(function() {

        $('#btn-save').click(function (event) {

            event.preventDefault();

            var tId = <?php echo $t_id; ?>;
            var tBonus = <?php echo $tournament_info['bonus'] ?>;

            $.ajax({
                type: "POST",
                url: "save_results.php",
                data: "action=validate&tId="+tId+"&tBonus="+tBonus,
                success: function(data) {
                    if(data.status == 'success'){
                        alert("Resultados salvos com sucesso!");
                    }else if(data.status == 'error'){
                        alert("Erro ao salvar resultados!");
                    }
                }
            });

        });

        $('#btn-cancel').click(function (event) {

            event.preventDefault();

            var tId = <?php echo $t_id; ?>;

            $.ajax({
                type: "POST",
                url: "save_results.php",
                data: "action=invalidate&tId="+tId,
                success: function(data) {
                    if(data.status == 'success'){
                        alert("Resultados salvos com sucesso!");
                    }else if(data.status == 'error'){
                        alert("Erro ao salvar resultados!");
                    }
                }
            });

        });

    });

</script>



