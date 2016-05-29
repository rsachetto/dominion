

<h3>Ranking</h3>
<br>
<table class="table table-bordered" id="ranking-table">
    <thead>
    <tr>
        <th>Posição</th>
        <th>Nome</th>
        <th>PRK1</th>
        <th>PRK2</th>
        <th>PRK3</th>
        <th>PRK4</th>
        <th>PRK5</th>
        <th>PRK6</th>
        <th>PRK7</th>
        <th>PRK8</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script type="text/javascript">
    jQuery(document).ready(function() {

        $('#ranking-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/get_ranking.php",
                "type": "POST"
            },
            columns: [
                {data: "position"},
                {data: "name"},
                {data: "prk1"},
                {data: "prk2"},
                {data: "prk3"},
                {data: "prk4"},
                {data: "prk5"},
                {data: "prk6"},
                {data: "prk7"},
                {data: "prk8"},
                {data: "total"}
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Portuguese-Brasil.json"
            }
        });

    });

</script>