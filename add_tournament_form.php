<?php
include('session.php');
include 'helpers.php';
$name = "";
$date = "";
$players = array();
$state = "";
$city= "";
$edit=false;

if(isset($_GET['t_id'])) {

    $edit = true;
    $t_id = $_GET['t_id'];
    $user_id = $_SESSION['user_id'];

    $tournament_info = get_tournament_info($dbh, $t_id);

    $players = get_tournament_players($dbh, $t_id);

    $name = $tournament_info['name'];
    $date = date( 'd/m/y H:i', strtotime($tournament_info['date']));
    $state = $tournament_info['state'];
    $city = $tournament_info['city'];
    $address = $tournament_info['address'];

    error_log($address);

}

if (!$edit)
    echo '<h2>Criar novo torneio</h2>';
else
    echo '<h2>Editar torneio</h2>';
?>


<form role="form" id="champ-form">
    <div class="form-group">
        <label for="name">Nome do torneio:</label>
        <input type="text" class="form-control" id="name" placeholder="Nome do torneio" value="<?php echo $name; ?>" >
    </div>
    <div class="form-group">
        <label for="datetimepicker">Data e Hora de realização:</label>
        <input type="text" class="form-control" id="datetimepicker" placeholder="Data de realização" value="<?php echo $date; ?>">
    </div>
    <div class="form-group">
        <label for="address">Endereço:</label>
        <textarea rows="4" cols="50" class="form-control" id="address" placeholder="Endereço"><?php if($address != '' ) echo $address; ?></textarea>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <label for="states">Estado: </label><select class="form-control" id="states"></select>
        </div>
        <div class="col-xs-4">
            <label for="cities">Cidade: </label>
            <select class="form-control" id="cities"></select>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group" id="div-table">
        <label for="players-table">Jogadores inscritos</label>
        <table class="table table-striped" id="players-table">
            <thead>
            <tr>
                <th>id</th>
                <th>Usuário</th>
                <th>Nome</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($players as $player) {
                $tr_id = '<td>'.$player['id'].'</td>';
                $tr_username = '<td>'.$player['username'].'</td>';
                $tr_name = '<td>'.$player['name'].'</td>';
                $tr_del = '<td><a class="remove" href="#" ><span class="glyphicon glyphicon-trash"></span></a></td>';
                echo '<tr>'.$tr_id.$tr_username.$tr_name.$tr_del.'</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="form-group" id="remote">
        <label for="typeahead-input">Adicionar Jogadores</label>
        <input type="text" id="typeahead-input" class="form-control" data-provide="typeahead" autocomplete="off">
    </div>
    <button type="submit" id="submit-bnt" class="btn btn-primary">Salvar</button>
</form>
<script type="text/javascript">

    function storeTblValues()
    {
        var TableData = [];

        $('#players-table').find('tr').each(function(row, tr){
            TableData[row]={
                "userId" : $(tr).find('td:eq(0)').text()
            }
        });
        TableData.shift();  // first row will be empty - so remove
        return TableData;
    }

    jQuery(document).ready(function() {

        var edit = <?php echo json_encode($edit);?>;
        if (!edit) $("#div-table").hide();

        $('#datetimepicker').datetimepicker({
            sideBySide: true
        });

        $.getJSON('js/estados_cidades.json', function (data) {
            var items = [];
            var options = '<option value="">escolha um estado</option>';
            $.each(data, function (key, val) {

                var state = <?php if($edit) echo '"'.$state.'"'; else echo "''";?>;
                if(val.nome == state)
                    options += '<option selected="selected" value="' + val.nome + '">' + val.nome + '</option>';
                else
                    options += '<option value="' + val.nome + '">' + val.nome + '</option>';
            });
            $("#states").html(options);

            $("#states").change(function () {

                var options_cidades = '';
                var str = "";

                $("#states option:selected").each(function () {
                    str += $(this).text();
                });

                $.each(data, function (key, val) {
                    if(val.nome == str) {
                        $.each(val.cidades, function (key_city, val_city) {
                            var city = <?php if($edit) echo '"'.$city.'"'; else echo "''";?>;
                            if(val_city == city)
                                options_cidades += '<option selected="selected" value="' + val_city + '">' + val_city + '</option>';
                            else
                                options_cidades += '<option value="' + val_city + '">' + val_city + '</option>';
                        });
                    }
                });
                $("#cities").html(options_cidades);

            }).change();

        });

        $('#typeahead-input').typeahead({
            source: function (query, process) {
                return $.get('/search_players.php?query=' + query, function (data) {
                    return process(data.suggestions);
                });
            },

            afterSelect: function (item) {
                $("#div-table").show();

                td1 = '<td>'+item.id+'</td>';
                td2 = '<td>'+item.username+'</td>';
                td3 = '<td>'+item.name+'</td>';
                td4 = '<td><a class="remove" href="#" ><span class="glyphicon glyphicon-trash"></span></a></td>'


                tr = '<tr>'+td1+td2+td3+td4+'</tr>';

                var data_in_table = false;

                $('#players-table').find('tr').each(function(row, tr){
                    if (item.id == $(tr).find('td:eq(0)').text()) {
                        data_in_table = true;
                        return false;
                    }
                });

                if(!data_in_table) {
                    $('#players-table').find('> tbody:last-child').append(tr);
                }

                $('#typeahead-input').val("");
            },

            displayText: function (item) {
                return item.name + " - " + item.username;
            }

        });

        $('#players-table').on('click', 'a[class="remove"]', function(e){
            $(this).closest('tr').remove();
            if($('#players-table tr').length == 1)  $("#div-table").hide();
        });

        $('#submit-bnt').click(function( event ) {

            console.log(edit);
            event.preventDefault();

            var ownerId = <?php echo $_SESSION['user_id']; ?>;

            var TableData;
            TableData =  JSON.stringify(storeTblValues());
            cName = $('#name').val();
            cDate = $('#datetimepicker').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm");
            cState = $( "#states option:selected" ).text();
            cCity = $( "#cities option:selected" ).text();
            cAddr = $('#address').val();

            <?php
            if(!$edit) {
                echo 'post_data = "players=" + TableData + "&cName=" + cName + "&cDate=" + cDate + "&ownerId=" + ownerId + "&cCity=" + cCity + "&cState=" + cState + "&cAddr=" + cAddr + "&edit=false";';
            }
            else {
                echo "var tid=".$t_id.";";
                echo 'post_data = "t_id=" + tid +"&players=" + TableData + "&cName=" + cName + "&cDate=" + cDate + "&ownerId=" + ownerId + "&cCity=" + cCity + "&cState=" + cState + "&cAddr=" + cAddr + "&edit=true";';
            }
            ?>
            $.ajax({
                type: "POST",
                url: "saveNewChampionship.php",
                data: post_data,
                success: function(data) {
                    if(data.status == 'success'){
                        alert("Campeonato adicionado com sucesso!");
                    }else if(data.status == 'error'){
                        alert("Erro ao salvar campeonato!");
                    }
                }
            });
        })
    });
</script>
