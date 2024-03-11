<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user = $_GET['user'];

    try {
        require 'connections.php';
        require 'friends_get_ctrl.php';
        require 'friends_get_model.php';
        require 'config_session.php';

        // Puhdistetaan haettu käyttäjätunnus
        $user = trim_input($user);

        // Haetaan käyttäjän id
        $result = get_user_id($pdo, $user);

        // Sidotaan tulos muuttujaan
        $user_id = $result['user_id'];
        $your_id = $_SESSION['user_id'];

        // Tarkistetaan onko käyttäjä olemassa
        if (!$user_id) {
            $_SESSION['friend_request_error'] = 'Käyttäjää ei löytynyt.';
            header('Location: ../lisaa_kaveri.php?käyttäjä_ei_olemassa');
            die();
        }

        // Tarkistetaan onko käyttäjä jo kaveri
        $friends = get_friends($pdo, $your_id);

        foreach ($friends as $friend) {
            if ($friend['user_id'] === $user_id) {
                $_SESSION['friend_request_error'] = 'Käyttäjä on jo kaverisi.';
                header('Location: ../lisaa_kaveri.php?käyttäjä=kaverisi');
                die();
            }
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
        echo 'Tietokantavirhe: ' . $e->getMessage();
    }

} else {
    header('Location: ../index.php?pääsy=kielletty');
    die();
}