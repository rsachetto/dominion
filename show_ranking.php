

<h3>Ranking</h3>
<br>
<select name="uf" id="uf" class="form-control input-sm">
    <option value="">Selecione</option>
    <option value="AC">Acre</option>
    <option value="AL">Alagoas</option>
    <option value="AP">Amapá</option>
    <option value="AM">Amazonas</option>
    <option value="BA">Bahia</option>
    <option value="CE">Ceará</option>
    <option value="DF">Distrito Federal</option>
    <option value="ES">Espirito Santo</option>
    <option value="GO">Goiás</option>
    <option value="MA">Maranhão</option>
    <option value="MS">Mato Grosso do Sul</option>
    <option value="MT">Mato Grosso</option>
    <option value="MG">Minas Gerais</option>
    <option value="PA">Pará</option>
    <option value="PB">Paraíba</option>
    <option value="PR">Paraná</option>
    <option value="PE">Pernambuco</option>
    <option value="PI">Piauí</option>
    <option value="RJ">Rio de Janeiro</option>
    <option value="RN">Rio Grande do Norte</option>
    <option value="RS">Rio Grande do Sul</option>
    <option value="RO">Rondônia</option>
    <option value="RR">Roraima</option>
    <option value="SC">Santa Catarina</option>
    <option value="SP">São Paulo</option>
    <option value="SE">Sergipe</option>
    <option value="TO">Tocantins</option>
</select>
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

        var oTable = $('#ranking-table').DataTable({
            "sDom": 'lrtip',
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

        $('#uf').on('change',function(){
            var selectedValue = $(this).val();
            oTable
                .search(this.value)
                .draw();
        });

    });



</script>