<?php include("session.php")?>


<h3>Campeonatos sem resultado</h3>
<br>
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

<h3>Campeonatos com resultados enviados</h3>
<br>
<table class="table table-bordered" id="sent-champs-table">
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


<h3>Campeonatos com resultados validados</h3>
<br>
<table class="table table-bordered" id="validated-champs-table">
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


<script type="text/javascript">
    jQuery(document).ready(function() {

        $('#open-champs-table').DataTable( {
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
                        $(nTd).html("<a href='#' class='results' id="+oData.id+">Enviar resultado</a>");
                    },
                    orderable: false
                },
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        } );

        $('#sent-champs-table').DataTable( {
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

    });

    $('#validated-champs-table').DataTable( {
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

    $('#sent-champs-table').on('click', 'a[class="results"]', function(e){
        var id = $(this).attr('id');
        $("#content").load("edit_results.php?t_id="+id+'&edit=true');
    });

    $('#validated-champs-table').on('click', 'a[class="results"]', function(e){
        var id = $(this).attr('id');
        $("#content").load("show_results.php?t_id="+id);
    });


</script>



