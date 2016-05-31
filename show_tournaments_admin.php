<?php include("session.php")?>

<ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#sent-results-tab-content" role="tab" data-toggle="tab">Campeonatos não sancionados</a></li>
    <li><a href="#validated-results-tab-content" role="tab" data-toggle="tab">Campeonatos sancionados</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="sent-results-tab-content">
        <br/>
        <table class="table table-bordered" id="open-champs-table" width="100%">
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

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable
                .tables( { visible: true, api: true } )
                .columns.adjust();
        });

    });

</script>



