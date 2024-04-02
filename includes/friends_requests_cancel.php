<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_GET['u'];

    try {
        require 'connections.php';
        require 'friends_ctrl.php';
        require 'friends_model.php';
        require 'config_session.php';

        // Sidotaan tulos muuttujaan (puhdistus)
        $user_id = htmlspecialchars(strip_tags($user_id));
        $your_id = $_SESSION['user_id'];

        // Tarkistetaan onko käyttäjä olemassa
        if (!user_exists($pdo, $user_id)) {
            $_SESSION['request_cancel_error'] = 'Käyttäjää ei löydy.';
            header('Location: ../lisaa_kaveri.php?kaveri=ei_loydy');
            die();
        }

        // Tarkistetaan onko käyttäjä jo kaveri
        if (is_friend($pdo, $your_id, $user_id)) {
            $_SESSION['request_cancel_error'] = 'Käyttäjä on jo kaverisi.';
            header('Location: ../lisaa_kaveri.php?virhe=on_jo_kaveri');
            die();
        }

        // Perutaan kaveripyyntö
        cancel_request($pdo, $your_id, $user_id);

        $_SESSION['request_cancel_success'] = 'Kaveripyyntö peruttu.';
        header('Location: ../lisaa_kaveri.php?peruutus=onnistui');
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