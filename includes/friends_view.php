<?php

try {
    include 'connections.php';
    include 'friends_ctrl.php';
    require_once 'friends_model.php';

    // Haetaan kaverit
    $friends = get_friends($pdo, $_SESSION['user_id']);

    // Käsitellään kaveridata
    $friends = array_map('process_friend_data', $friends);

} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
}
