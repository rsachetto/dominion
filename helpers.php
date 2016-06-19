<?php


/*
5 PRk por participar do evento,
4 PRk para cada segundo lugar em mesas,
8 PRk para cada primeiro lugar nas mesas,
X PRK para finalistas e para o vencedor do torneio, valor esse que irá variar conforme tamanho do evento; 
Para os finalistas e para o campeão são atribuídos PRK adicionais, além dos pontos normalmente conquistados:
90 para o campeão;
30 para os finalistas e;
10 para semifinalista.
Porém, quanto maior o torneio, maior a quantidade de pontos. Os pontos acima são multiplicados por um coeficiente que leva em consideração a quantidade de jogadores.
Em um campeonato com 15 jogadores o coeficiente é 01. Abaixo dessa quantidade o bônus sempre será o mesmo:
Coef = log15(NumPlayers2) – 1 (arredondado para baixo)
Em um campeonato com 50 jogadores, por exemplo, ficaria assim:
log15(2500) - 1 = 1.889
O Campeão ganharia um bônus de 1.889 * 90 = 170 PRK
*/
function update_prk($dbh, $players, $tournament_id, $bonus) {

    $result = true;

    foreach ($players as $player) {

        $prk = 5.0 + 4.0 * intval($player['num_second_places']) + 8.0 * intval($player['num_first_places']);
        $prk += intval($player['champion']) * 90.0 + intval($player['finalist']) * 30.0 + intval($player['semi_finalist']) * 10.0;
        $prk = $prk * $bonus;

        $stmt = $dbh->prepare("UPDATE dominion.tournament_has_user SET prk=:prk WHERE tournament_id=:t_id AND user_id=:user_id");
        $stmt->bindParam(':user_id', $player['id'], PDO::PARAM_INT);
        $stmt->bindParam(':t_id', $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(':prk', $prk);

        $result &= $stmt->execute();

    }

    return $result;
    
}

function calc_bonus($num_players) {
    if ($num_players > 15) {
        return log(pow($num_players,2.0), 15) - 1;
    }

    return 1;
}

function get_tournament_info($dbh, $t_id) {
    // do query
    $stmt = $dbh->prepare('SELECT id, date, name, city, state, address, bonus FROM dominion.tournament WHERE id=:t_id');
    $stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_user_info($dbh, $u_id) {
    // do query
    $stmt = $dbh->prepare('SELECT username, email, name, city, state FROM dominion.user WHERE id=:u_id');
    $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_tournament_players_with_scores($dbh, $t_id) {
    $stmt = $dbh->prepare('select user.id, user.username, user.name, tournament_has_user.num_first_places, tournament_has_user.num_second_places, tournament_has_user.champion, tournament_has_user.finalist, tournament_has_user.semi_finalist
                           from dominion.user
                              join dominion.tournament_has_user on (user.id = tournament_has_user.user_id)
                              join dominion.tournament on (tournament_has_user.tournament_id = :t_id)
                              GROUP BY user.id;');

    $stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}

function get_tournament_players($dbh, $t_id)
{
    $stmt = $dbh->prepare('SELECT id, username, name FROM dominion.user WHERE id IN (SELECT user_id FROM dominion.tournament_has_user WHERE tournament_id=:t_id)');
    $stmt->bindParam(':t_id', $t_id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}