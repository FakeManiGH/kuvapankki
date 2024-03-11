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
        if ($user_id == null) {
            $_SESSION['request_cancel_error'] = 'Käyttäjää ei löytynyt.';
            header('Location: ../lisaa_kaveri.php?virhe=ei_kayttajaa');
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
        echo 'Tietokantavirhe: ' . $e->getMessage();
    }
} else {
    header('Location: ../index.php?pääsy=kielletty');
    die();
}