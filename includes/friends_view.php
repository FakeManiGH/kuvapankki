<?php

try {
    include 'connections.php';
    include 'friends_get_ctrl.php';
    require_once 'friends_get_model.php';

    // Haetaan kaverit
    $friends = get_friends($pdo, $_SESSION['user_id']);

    // Puhdistetaan hakutulokset
    $friends = array_map('process_friend_data', $friends);

} catch (PDOException $e) {
    echo 'Tietokantavirhe: ' . $e->getMessage();
}
