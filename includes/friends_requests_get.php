<?php

include 'includes/connections.php';
include 'includes/friends_ctrl.php';
include 'includes/friends_model.php';

try {
    // Haetaan saapuneet kaveripyynnöt
    $requests = get_incoming_requests($pdo, $_SESSION['user_id']);

    // Haetaan lähetetyt kaveripyynnöt
    $sent_requests = get_sent_requests($pdo, $_SESSION['user_id']);

    // Suljetaan tietokantayhteys ja nollataan muuttujat
    $stmt = null;

} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
}