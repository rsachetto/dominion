<table class="table" id="tournament-table" style="font-size: 16px;">
    <tbody>
    <tr><td colspan="2">Evento: <?php echo $tournament_info['name']." (".$tournament_info['city']." - ".$tournament_info['state'].") "; ?></td><td>Data e Hora: <?php echo date( 'd/m/y H:i', strtotime($tournament_info['date']));?></td>
</tr>
<tr><td colspan="3">Endereço: <?php echo $tournament_info['address'] ?></td></tr>
<tr>
    <td>Quantidade de jogadores: <?php echo $num_players; ?></td>
    <td>Coeficiente: <?php echo $tournament_info['bonus']; ?></td>
</tr>
</tbody>
</table>