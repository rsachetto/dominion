<?php include("session.php")?>

<ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#sent-results-tab-content" role="tab" data-toggle="tab">Organizadores</a></li>
    <li><a href="#validated-results-tab-content" role="tab" data-toggle="tab">Todos os usuários</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="sent-results-tab-content">
        <br/>
        <table class="table table-bordered" id="organizers-table" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="validated-results-tab-content" >
        <br/>
        <table class="table table-bordered" id="all-users-table" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Nome</th>
                <th>Email</th>
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

        var oTable = $('#organizers-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_organizers.php",
                "type": "POST",
                "data": {
                    "organizer": "true"
                }
            },
            columns: [
                {data: "id"},
                {data: "username"},
                {data: "name"},
                {data: "email"},
                {
                    data: null,
                    //defaultContent: '<a href="#" class="remove">Enviar resultado</a>',
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='results' id=" + oData.id + ">Descredenciar</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });


        oTable.on('click', 'a[class="results"]', function (e) {
            var id = $(this).attr('id');

            if(confirm("Tem certeza que deseja remover o organizador?")) {
                $.ajax({
                    type: "POST",
                    url: "change_organizer.php",
                    data: "uid=" + id+"&new_role=player",
                    success: function(data) {
                        if(data.status == 'success'){
                            alert("Mudança realizada com sucesso!");
                            oTable.ajax.reload();
                            uTable.ajax.reload();
                        }else if(data.status == 'error'){
                            alert("Erro ao realizar mudança!");
                        }
                    }
                });
            }

        });

        var uTable = $('#all-users-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/search_organizers.php",
                "type": "POST",
                "data": {
                    "organizer": "false"
                }
            },
            columns: [
                {data: "id"},
                {data: "username"},
                {data: "name"},
                {data: "email"},
                {
                    data: null,
                    //defaultContent: '<a href="#" class="remove">Enviar resultado</a>',
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='#' class='results' id=" + oData.id + ">Adicionar organizador</a>");
                    },
                    orderable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });


        uTable.on('click', 'a[class="results"]', function (e) {
            var id = $(this).attr('id');
            if(confirm("Tem certeza que deseja promover este usuário?")) {
                $.ajax({
                    type: "POST",
                    url: "change_organizer.php",
                    data: "uid=" + id+"&new_role=organizer",
                    success: function(data) {
                        if(data.status == 'success'){
                            alert("Mudança realizada com sucesso!");

                            uTable.ajax.reload();
                            oTable.ajax.reload();

                        }else if(data.status == 'error'){
                            alert("Erro ao realizar mudança!");
                        }
                    }
                });
            }
            
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable
                .tables( { visible: true, api: true } )
                .columns.adjust();
        });

    });

</script>



