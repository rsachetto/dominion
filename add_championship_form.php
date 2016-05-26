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

        $("#div-table").hide();

        $('#datetimepicker').datetimepicker({
            format: 'L'
        });

        $.getJSON('js/estados_cidades.json', function (data) {
            var items = [];
            var options = '<option value="">escolha um estado</option>';
            $.each(data, function (key, val) {
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

//        $("tr td a .remove").on("click", function(){
//            console.log("REMOVE");
//            $(this).parent("tr:first").remove()
//        });

        $('#players-table').on('click', 'a[class="remove"]', function(e){
            $(this).closest('tr').remove()
        })

        $('#submit-bnt').click(function( event ) {

            event.preventDefault();

            var ownerId = <?php echo $_SESSION['user_id']; ?>;

            var TableData;
            TableData =  JSON.stringify(storeTblValues());
            cName = $('#name').val();
            cDate = $('#datetimepicker').data("DateTimePicker").date().format("YYYY-MM-DD");
            cState = $( "#states option:selected" ).text();
            cCity = $( "#cities option:selected" ).text();

            console.log(cState);

            $.ajax({
                type: "POST",
                url: "saveNewChampionship.php",
                data: "players=" + TableData+"&cName="+cName+"&cDate="+cDate+"&ownerId="+ownerId+"&cCity="+cCity+"&cState="+cState,
                success: function(data) {
                    if(data.status == 'success'){
                        alert("Thank you for subscribing!");
                    }else if(data.status == 'error'){
                        alert("Error on query!");
                    }
                }
            });
        })
    });
</script>
