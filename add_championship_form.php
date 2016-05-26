<?php
include('session.php');
?>
<form role="form" id="champ-form">
    <div class="form-group">
        <label for="name">Nome do torneio:</label>
        <input type="text" class="form-control" id="name" placeholder="Nome do torneio">
    </div>
    <div class="form-group">
        <label for="datetimepicker">Data de realização:</label>
        <input type="text" class="form-control" id="datetimepicker" placeholder="Data de realização">
    </div>
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker').datetimepicker({
                format: 'L'
            });
        });
    </script>
    <div class="form-group">
    <label for="estado">Estado</label>:<select class="form-control id="estado" name="estado"></select>
        <label for="cidade">Cidade</label>:<select class="form-control id="cidade" name="cidade"></select>
    </div>

    <div class="clearfix"></div>
    <div class="form-group">
        <label for="players-table">Jogadores inscritos</label>
        <table class="table table-striped" id="players-table">
            <thead>
            <tr>
                <th>id</th>
                <th>Usuário</th>
                <th>Nome</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="form-group" id="remote">
        <label for="typeahead-input">Adicionar Jogadores</label>
        <input type="text" id="typeahead-input" class="form-control" data-provide="typeahead" autocomplete="off">
    </div>
    <script type="text/javascript" src="js/cidades-estados-v0.2.js"></script>

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

            new dgCidadesEstados(
                document.getElementById('estado'),
                document.getElementById('cidade'),
                true
            );

            $('#typeahead-input').typeahead({
                source: function (query, process) {
                    return $.get('/search_players.php?query=' + query, function (data) {
                        return process(data.suggestions);
                    });
                },
                afterSelect: function (item) {
                    td1 = '<td>'+item.id+'</td>';
                    td2 = '<td>'+item.username+'</td>';
                    td3 = '<td>'+item.name+'</td>';

                    tr = '<tr>'+td1+td2+td3+'</tr>';

                    $('#players-table').find('> tbody:last-child').append(tr);
                },

                displayText: function (item) {
                    return item.name + " - " + item.username;
                }

            });

            $('#submit-bnt').click(function( event ) {
                //Get raw HTML of tbody in the data table
                //var table = $('#players-table tbody').html();

                event.preventDefault();

                var ownerId = <?php echo $_SESSION['user_id']; ?>;

                var TableData;
                TableData =  JSON.stringify(storeTblValues());
                cName = $('#name').val();
                cDate = $('#datetimepicker').data("DateTimePicker").date().format("YYYY-MM-DD");
                console.log(cDate);

                $.ajax({
                    type: "POST",
                    url: "saveNewChampionship.php",
                    data: "players=" + TableData+"&cName="+cName+"&cDate="+cDate+"&ownerId="+ownerId
//                    success: function(msg){
//                        console.log("YEYYYAAAAA");
//                    }
                });
            })
        });
    </script>

    <button type="submit" id="submit-bnt" class="btn btn-default">Salvar</button>
</form>
