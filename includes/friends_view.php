<?php

try {
    include 'connections.php';
    include 'friends_ctrl.php';
    require_once 'friends_model.php';

    // Retrieve friends from the database
    $friends = get_friends($pdo, $_SESSION['user_id']);

    // Process the data (clean it up and make it safe to display)
    $friends = array_map('process_friend_data', $friends);

    // Close the connection
    $pdo = null;

} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
}
