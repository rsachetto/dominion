<?php include("session.php")?>

<ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="active"><a id="no-results-tab" href="#no-results-tab-content" role="tab" data-toggle="tab">Campeonatos com inscrição aberta</a></li>
    <li><a href="#sent-results-tab-content" role="tab" data-toggle="tab">Campeonatos que estou inscrito</a></li>
    <li><a href="#validated-results-tab-content" role="tab" data-toggle="tab">Campeonatos finalizados</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="no-results-tab-content">
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

        $('#open-champs-table').DataTable( {
            //responsive: true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "player_query": "open",
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
                        $(nTd).html("<a href='#' class='action' id="+oData.id+">Ver torneio</a>");
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
                    "player_query": "subscribed",
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
                        $(nTd).html("<a href='#' class='action' id="+oData.id+">Ver torneio</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        } );

        $('#validated-champs-table').DataTable( {
            //responsive: true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_tournaments.php",
                "type": "POST",
                "data": {
                    "player_query": "validated",
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
                        $(nTd).html("<a href='#' class='action' id="+oData.id+">Ver resultado</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });

        $('#open-champs-table').on('click', 'a[class="action"]', function(e){
            var id = $(this).attr('id');
            var user_id = <?php echo $_SESSION['user_id'];?>;
            $("#content").load("show_tournament.php?t_id="+id+"&user_id="+user_id);
        });

        $('#sent-champs-table').on('click', 'a[class="action"]', function(e){
            var id = $(this).attr('id');
            var user_id = <?php echo $_SESSION['user_id'];?>;

            $("#content").load("show_tournament.php?t_id="+id+"&user_id="+user_id);
        });

        $('#validated-champs-table').on('click', 'a[class="action"]', function(e){
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



