<?php

if (!isset($pwd_token) || !empty($pwd_token)) {
    include 'includes/connections.php';
    require_once 'includes/pwd_reset_ctrl.php';
    require_once 'includes/pwd_reset_model.php';

    // Puhdistetaan token vertailua varten (tietoturvasyistä)
    $pwd_token = trim_input($_GET['pwd_token']);

    try {
        $result = get_token($pdo, $pwd_token);

        // Tarkistetaan onko token olemassa ja onko se vanhentunut
        if (empty($result)) {
            $token_error = "Virheellinen tai vanhentunut nollauslinkki!";
        }
        if (!tokens_match($result['token'], $pwd_token)) {
            $token_error = "Virheellinen tai vanhentunut nollauslinkki!";
        }

        // Suljetaan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;

    } catch (PDOException $e) {
        error_log("PDOException: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }
    
} else {
    
    $token_error = "Virheellinen tai vanhentunut nollauslinkki!";   
}