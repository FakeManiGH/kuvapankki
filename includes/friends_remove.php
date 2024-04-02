<?php
include 'config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include 'connections.php';
    require 'friends_model.php';

    try {
        // Tallentaa käyttäjän id ja kaverin id muuttujiin
        $user_id = $_SESSION['user_id'];
        $friend_id = $_GET['u'];

        // Puhdistetaan muuttujat tietoturvasyistä.
        $user_id = htmlspecialchars(strip_tags($user_id));
        $friend_id = htmlspecialchars(strip_tags($friend_id));

        // Tarkistaa onko kaveri olemassa
        if (!is_friend($pdo, $user_id, $friend_id)) {
            $_SESSION['friend_error'] = 'Kaveria ei löydy';
            header('Location: ../kaverit.php?kaveria=ei_loydy');
            die();
        }

        // Poistaa kaverin
        remove_friend($pdo, $user_id, $friend_id);

        // Poistaa kaverin myös toiselta käyttäjältä
        remove_friend_from_friend($pdo, $user_id, $friend_id);

        // Uudelleenohjaa käyttäjän kaverit sivulle
        $_SESSION['friend_success'] = 'Kaveri poistettu';
        header('Location: ../kaverit.php?kaveri=poistettu');
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