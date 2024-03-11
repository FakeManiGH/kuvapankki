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
            $_SESSION['request_accept_error'] = 'Käyttäjää ei löytynyt.';
            header('Location: ../lisaa_kaveri.php?virhe=ei_kayttajaa');
            die();
        }

        // Tarkistetaan onko käyttäjä jo kaveri
        if (is_friend($pdo, $your_id, $user_id)) {
            $_SESSION['request_accept_error'] = 'Käyttäjä on jo kaverisi.';
            header('Location: ../lisaa_kaveri.php?virhe=on_jo_kaveri');
            die();
        }

        // Hyväksytään kaveripyyntö
        add_friend($pdo, $your_id, $user_id);

        // Lisätään kaveri myös toiselle käyttäjälle
        add_friend($pdo, $user_id, $your_id);

        // Poistetaan kaveripyyntö
        cancel_request($pdo, $user_id, $your_id);

        // Poistetaan kaveripyyntö myös toiselta käyttäjältä, jos sellainen on
        cancel_request($pdo, $your_id, $user_id);

        $_SESSION['request_accept_success'] = 'Kaveripyyntö hyväksytty. Käyttäjä on nyt kaverisi.';
        header('Location: ../lisaa_kaveri.php?hyvaksynta=onnistui');
        die();

    } catch (PDOException $e) {
        echo 'Tietokantavirhe: ' . $e->getMessage();
    }

} else {
    header('Location: ../index.php?pääsy=kielletty');
    die();
}