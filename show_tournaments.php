<?php include("session.php")?>

<ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="active"><a id="no-results-tab" href="#no-results-tab-content" role="tab" data-toggle="tab">Campeonatos sem resultado</a></li>
    <li><a href="#sent-results-tab-content" role="tab" data-toggle="tab">Campeonatos enviados</a></li>
    <li><a href="#validated-results-tab-content" role="tab" data-toggle="tab">Campeonatos validados</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="no-results-tab-content">
        <br/>
        <table class="table table-bordered" id="open-champs-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="sent-results-tab-content">
        <br/>
        <table class="table table-bordered" id="sent-champs-table" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="validated-results-tab-content" >
        <br/>
        <table class="table table-bordered" id="validated-champs-table" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function() {

        var open_champs_table = $('#open-champs-table').DataTable( {
            //responsive: true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "submitted": "false",
                    "user_id": <?php echo $_SESSION['user_id']; ?>
                }
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "date" },
                { data: "city" },
                { data: "state" },
                {
                    data: null,
                    //defaultContent: '<a href="#" class="remove">Enviar resultado</a>',
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='edit' id="+oData.id+">Editar torneio</a> | <a href='#' class='results' id="+oData.id+">Enviar resultado</a>");
                    },
                    orderable: false
                },
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        } );

        var sent_champs_table  = $('#sent-champs-table').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "submitted": "true",
                    "user_id": <?php echo $_SESSION['user_id']; ?>
                }
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "date" },
                { data: "city" },
                { data: "state" },
                {
                    data: null,
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='results' id="+oData.id+">Editar resultado</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        } );

        var validated_champs_table = $('#validated-champs-table').DataTable( {
            //responsive: true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "submitted": "true",
                    "validated": "true",
                    "user_id": <?php echo $_SESSION['user_id']; ?>
                }
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "date" },
                { data: "city" },
                { data: "state" },
                {
                    data: null,
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='results' id="+oData.id+">Ver resultado</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });

        $('#open-champs-table').on('click', 'a[class="results"]', function(e){
            var id = $(this).attr('id');
            $("#content").load("edit_results.php?t_id="+id);
        });

        $('#open-champs-table').on('click', 'a[class="edit"]', function(e){
            var id = $(this).attr('id');
            $("#content").load("add_tournament_form.php?t_id="+id);
        });

        $('#sent-champs-table').on('click', 'a[class="results"]', function(e){
            var id = $(this).attr('id');
            $("#content").load("edit_results.php?t_id="+id+'&edit=true');
        });

        $('#validated-champs-table').on('click', 'a[class="results"]', function(e){
            var id = $(this).attr('id');
            $("#content").load("show_results.php?t_id="+id);
        });


        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable
                .tables( { visible: true, api: true } )
                .columns.adjust();
        })
    });



</script>



