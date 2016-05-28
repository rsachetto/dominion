<?php

function update_rank() {
    $stmt = $dbh->prepare('select tournament.date, user.username, user.name, tournament_has_user.tournament_id, 
                           tournament_has_user.num_first_places, tournament_has_user.num_second_places, 
                           tournament_has_user.champion, tournament_has_user.finalist, tournament_has_user.semi_finalist
                           from user
                           join tournament_has_user on (user.id = tournament_has_user.user_id)
                           join tournament on (tournament.id = tournament_has_user.tournament_id)
');

    $stmt->execute();

    $players = array();
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_players = count($players);

    error_log(print_r($players, true));
    
}

function calc_bonus($num_players) {
    if ($num_players > 15) {
        return log(pow($num_players,2.0), 15) - 1;
    }

    return 1;

}