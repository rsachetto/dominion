<?php
include('session.php');
include('helpers.php');

$t_id = $_GET['t_id'];
$user_id = $_SESSION['user_id'];
$edit = '0';

if(isset($_GET['edit'])) {
    $edit = '1';
}

$tournament_info = get_tournament_info($dbh, $t_id);
$players = array();
// do query
if($edit) {

    $players = get_tournament_players_with_scores($dbh, $t_id);
}
else {
    $players = get_tournament_players($dbh, $t_id);
}

$num_players = count($players);

$tournament_info['bonus'] = calc_bonus($num_players);

?>

<?php include 'tournament_header.php'; ?>
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
            $tr_num_first = '<td><input id="num_first" type="text" style="width: 60px;"/></td>';
            $tr_num_second = '<td><input type="text" style="width: 60px;" /></td>';
            $tr_semi = '<td><input type="checkbox" /></td>';
            $tr_finalist = '<td><input type="checkbox"/></td>';
            $tr_champ = '<td><input type="checkbox" /></td>';
            echo '<tr>'.$tr_name.$tr_num_first.$tr_num_second.$tr_semi.$tr_finalist.$tr_champ.'</tr>';
        }
    ?>
    </tbody>
</table>

<button class="btn btn-primary" id="btn-save">Enviar resultado</button>

<script type="text/javascript">

    jQuery(document).ready(function() {

        var edit = <?php echo $edit; ?>;
        var players_data = <?php echo json_encode($players); ?>;

        if(edit == 1) {
            
            $('#players-table').find('tbody>tr').each(function(row, tr){
                $(tr).find('td:eq(1) input').val(players_data[row]['num_first_places']);
                $(tr).find('td:eq(2) input').val(players_data[row]['num_second_places']);

                if(players_data[row]['semi_finalist'] == 1) {
                    $(tr).find('td:eq(3) input').prop('checked', true);
                }

                if(players_data[row]['finalist'] == 1) {
                    $(tr).find('td:eq(4) input').prop('checked', true);
                }

                if(players_data[row]['champion'] == 1) {
                    $(tr).find('td:eq(5) input').prop('checked', true);
                }

            });
        }

        $('#btn-save').click(function (event) {

            event.preventDefault();

            var tId = <?php echo $t_id; ?>;

            $('#players-table').find('tbody>tr').each(function(row, tr){
                nf = $(tr).find('td:eq(1) input').val();
                ns = $(tr).find('td:eq(2) input').val();
                semi = $(tr).find('td:eq(3) input').is(':checked');
                final = $(tr).find('td:eq(4) input').is(':checked');
                champion = $(tr).find('td:eq(5) input').is(':checked');

                players_data[row]['num_first_places'] = nf || 0;
                players_data[row]['num_second_places'] = ns || 0;
                players_data[row]['semi_finalist'] = semi || 0;
                players_data[row]['finalist'] = final || 0;
                players_data[row]['champion'] = champion || 0;

            });

            $.ajax({
                type: "POST",
                url: "save_results.php",
                data: "players=" + JSON.stringify(players_data)+"&tId="+tId,
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