<form role="form">
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
    <script type="text/javascript">
        jQuery(document).ready(function() {
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

                    $('#players-table > tbody:last-child').append(tr);
                },

                displayText: function (item) {
                    return item.name + " - " + item.username;
                }

            });

            $('#submit').click(function() {
                //Get raw HTML of tbody in the data table
                var table = $('#players-table tbody').html();

                for (var i = 0, row; row = table.rows[i]; i++) {
                    //iterate through rows
                    //rows would be accessed using the "row" variable assigned in the for loop
                    for (var j = 0, col; col = row.cells[j]; j++) {
                        //iterate through columns
                        //columns would be accessed using the "col" variable assigned in the for loop
                        console.log(col);
                    }
                }

//                //build form HTML (hide keeps the form from being visible)
//                $form = $('<form/>').attr({method: 'POST', action: ''}).hide();
//                //build textarea HTML
//                $textarea = $('<textarea/>').attr({name: 'data_rows'}).val(data_rows);
//                //add textarea to form
//                $form.append($textarea);
//                //add form to the document body
//                $('body').append($form);
//                //submit the form
//                $form.submit();
            });
        });
    </script>

    <button type="submit" class="btn btn-default">Salvar</button>
</form>
