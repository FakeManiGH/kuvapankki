<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $search_input = $_POST['search_user'];

    try {
        require 'connections.php';

        // Puhdistaa näytettävät tiedot
        $search_input = trim_input($search_input);

        // Syötteen minimi ja maksimipituus
        if (!input_length($search_input, 3, 40)) {
            $_SESSION['search_error'] = 'Hakusanan tulee olla 3-40 merkkiä pitkä.';
            header('Location: lisaa_kaveri.php?virhe.');
            die();
        }
        
        // haetaan käyttäjät
        $user_list = search_users($pdo, $search_input);

        // poistetaan oma käyttäjätunnus listasta, jos se löytyy.
        foreach ($user_list as $key => $user) {
            if ($user['username'] == $_SESSION['username']) {
                unset($user_list[$key]);
            }
        }

        // puhdistetaan ulos menevät tiedot
        $user_list = array_values($user_list);

        // nollataan tietokantayhteys
        $stmt = null;

    } catch (PDOException $e) {
        echo 'Tietokantavirhe: ' . $e->getMessage();
    }
        
} else {

    header('Location: ../index.php?pääsy=kielletty');
    die();
}