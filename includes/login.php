<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pwd = $_POST['pwd'];
        
    try {
        include 'connections.php';
        include 'login_model.php';
        require_once 'login_ctrl.php';
        require_once 'config_session.php';

        $errors = [];

        // Jos käyttäjän syötteet ovat tyhjiä, palauta takaisin kirjautumissivulle.
        if (is_input_empty($username, $pwd)) {
            $errors['empty_input'] = 'Täytä kaikki kentät!';
            $_SESSION['errors_login'] = $errors;
            header ("Location: ../kirjaudu.php?tyhjä=kenttä");
            die();
        }

        // Puhdistetaan käyttäjän syötteet
        $username = trim_input($username);
        $pwd = trim_input($pwd);

        // Haetaan käyttäjän tiedot tietokannasta
        $result = get_user($pdo, $username);

        // Jos käyttäjätunnusta ei löydy, palauta takaisin kirjautumissivulle.
        if (empty($result)) {
            $errors['login_incorrect'] = 'Virheellinen käyttäjätunnus tai salasana!';
            $_SESSION['errors_login'] = $errors;
            header ("Location: ../kirjaudu.php?kirjautuminen=epäonnistui");
            die();
        }

        // Tarkistetaan täsmääkö käyttäjätunnus ja salasana.
        if (!is_username_correct($username, $result['username'])) { 
            $errors['login_incorrect'] = 'Virheellinen käyttäjätunnus tai salasana!';
        }
        if (!password_verify($pwd, $result['pwd'])) {
            $errors['login_incorrect'] = 'Virheellinen käyttäjätunnus tai salasana!';
        }

        // Tarkistetaan onko käyttäjä vahvistettu
        if (!is_user_verified($result['verified'])) {
            $errors['login_incorrect'] = 'Käyttäjätunnus ei ole aktiivinen, vahvista sähköpostiosoite!';
        }

        // Jos virheitä, palauta takaisin kirjautumissivulle.
        if ($errors) {
            $_SESSION['errors_login'] = $errors;

            header ("Location: ../kirjaudu.php?kirjautuminen=epäonnistui");
            die();
        }

        // Jos kaikki on ok, luodaan uusi sessio ja ohjataan käyttäjä etusivulle.
        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $result['user_id'];
        session_id($sessionId);

        $_SESSION['user_id'] = htmlspecialchars($result['user_id']);
        $_SESSION['user_img'] = htmlspecialchars($result['user_img']);
        $_SESSION['username'] = htmlspecialchars($result['username']);
        $_SESSION['phone'] = htmlspecialchars($result['phone']);
        $_SESSION['email'] = htmlspecialchars($result['email']);
        $_SESSION['last_regeneration'] = time();

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;
        header('Location: ../selaa.php?kirjautuminen=onnistui');
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
