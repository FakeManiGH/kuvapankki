<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_GET['u'];

    try {
        require 'connections.php';
        require 'friends_ctrl.php';
        require 'friends_model.php';
        require 'config_session.php';

        // Puhdistetaan muuttujat
        $user_id = htmlspecialchars($user_id);
        $your_id = htmlspecialchars($_SESSION['user_id']);

        // Tarkistetaan onko käyttäjä olemassa
        if (!user_exists($pdo, $user_id)) {
            $_SESSION['friend_request_error'] = 'Käyttäjää ei löydy.';
            header('Location: ../lisaa_kaveri.php?käyttäjää_ei_loydy');
            die();
        }

        // Tarkistetaan onko käyttäjä jo kaveri
        if (is_friend($pdo, $your_id, $user_id)) {
            $_SESSION['friend_request_error'] = 'Käyttäjä on jo kaverisi.';
            header('Location: ../lisaa_kaveri.php?käyttäjä_on_jo_kaveri');
            die();
        }

        // Tarkistetaan onko käyttäjä jo lähettänyt kaveripyyntöä
        if (request_already_sent($pdo, $your_id, $user_id)) {
            $_SESSION['friend_request_error'] = 'Olet jo lähettänyt kaveripyynnön käyttäjälle.';
            header('Location: ../lisaa_kaveri.php?kaveripyyntö_jo_lähetetty');
            die();
        }

        // Lähetetään kaveripyyntö
        send_friend_request($pdo, $your_id, $user_id);

        // Ohjataan käyttäjä takaisin lisää kaveri -sivulle
        $_SESSION['friend_request_success'] = 'Kaveripyyntö lähetetty onnistuneesti.';
        header('Location: ../lisaa_kaveri.php?kaveripyyntö=lähetetty');
        die();

        
    } catch (PDOException $e) {
        error_log("PDOException: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }

} else {
    
    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}