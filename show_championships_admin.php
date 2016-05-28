<?php include("session.php")?>


<h3>Campeonatos não validados</h3>
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

<h3>Campeonatos validados</h3>
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

        $('#open-champs-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "submitted": "true"
                }
            },
            columns: [
                {data: "id"},
                {data: "name"},
                {data: "date"},
                {data: "city"},
                {data: "state"},
                {
                    data: null,
                    //defaultContent: '<a href="#" class="remove">Enviar resultado</a>',
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='results' id=" + oData.id + ">Ver resultados</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });


        $('#open-champs-table').on('click', 'a[class="results"]', function (e) {
            var id = $(this).attr('id');
            $("#content").load("show_results.php?validating=1&t_id=" + id);
        });

        $('#validated-champs-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "submitted": "true",
                    "validated": "true"
                }
            },
            columns: [
                {data: "id"},
                {data: "name"},
                {data: "date"},
                {data: "city"},
                {data: "state"},
                {
                    data: null,
                    //defaultContent: '<a href="#" class="remove">Enviar resultado</a>',
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='results' id=" + oData.id + ">Ver resultados</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });


        $('#validated-champs-table').on('click', 'a[class="results"]', function (e) {
            var id = $(this).attr('id');
            $("#content").load("show_results.php?valid=1&t_id=" + id);
        });

    });

</script>



