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

            $('#submit').click(function( event ) {
                //Get raw HTML of tbody in the data table
                //var table = $('#players-table tbody').html();
                event.preventDefault();

                var TableData;
                TableData = $.toJSON(storeTblValues());
                cName = $('#name').val();
                cData = $('#datetimepicker').val();

                postData = '{players: ' + TableData+", cName: "+cName+", cData:"  + cData + '}'
                console.log(postData);

//                $.ajax({
//                    type: "POST",
//                    url: "saveNewChampionship.php",
//                    data: "players=" + TableData+"&cName="+cName+"&cData="+cData,
//                    success: function(msg){
//                        // return value stored in msg variable
//                    }

                function storeTblValues()
                {
                    var TableData = new Array();

                    $('#players-table tr').each(function(row, tr){
                        TableData[row]={
                            "userId" : $(tr).find('td:eq(0)').text(),
                            "username" :$(tr).find('td:eq(1)').text(),
                            "name" : $(tr).find('td:eq(2)').text()
                        }
                    });
                    TableData.shift();  // first row will be empty - so remove
                    return TableData;
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
