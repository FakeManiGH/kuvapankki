<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pwd = $_POST['pwd'];


    // input sanitization function
        
    try {
        include 'connections.php';
        include 'login_model.php';
        require_once 'login_ctrl.php';
        require_once 'config_session.php';

        $errors = [];

        // Tarkistetaan onko käyttäjän syötteet tyhjiä
        if (is_input_empty($username, $pwd)) {
            $errors['empty_input'] = 'Täytä kaikki kentät!';
        }

        // Puhdistetaan käyttäjän syötteet
        $username = trim_input($username);
        $pwd = trim_input($pwd);

        // Haetaan käyttäjän tiedot tietokannasta
        $result = get_user($pdo, $username);

        // Tarkistetaan onko käyttäjätunnus ja salasana oikea ja onko käyttäjä vahvistettu
        if (!is_username_correct($username, $result['username'])) { 
            $errors['login_incorrect'] = 'Virheellinen käyttäjätunnus tai salasana!';
        }
        if (!password_verify($pwd, $result['pwd'])) {
            $errors['login_incorrect'] = 'Virheellinen käyttäjätunnus tai salasana!';
        }
        if (!is_user_verified($result['verified'])) {
            $errors['login_incorrect'] = 'Käyttäjätunnus ei ole aktiivinen, vahvista sähköpostiosoite!';
        }

        // Jos virheitä, palauta takaisin kirjautumissivulle.
        if ($errors) {
            $_SESSION['errors_login'] = $errors;

            header ("Location: kirjaudu.php");
            die();
        }

        // Jos kaikki on ok, luodaan uusi sessio ja ohjataan käyttäjä etusivulle.
        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $result['user_id'];
        session_id($sessionId);

        $_SESSION['user_id'] = htmlspecialchars($result['user_id']);
        $_SESSION['username'] = htmlspecialchars($result['username']);
        $_SESSION['first_name'] = htmlspecialchars($result['first_name']);
        $_SESSION['last_name'] = htmlspecialchars($result['last_name']);
        $_SESSION['phone'] = htmlspecialchars($result['phone']);
        $_SESSION['email'] = htmlspecialchars($result['email']);
        $_SESSION['last_regeneration'] = time();

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;
        header('Location: index.php?kirjautuminen=onnistui');
        die();
    
    } catch (PDOException $e) {
        die("Virhe tietokantakyselyssä: " . $e->getMessage());
    }
} else {
    
    header("Location: kirjaudu.php?pääsy=estetty");
    exit();
}
