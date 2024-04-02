<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pwd = $_POST['pwd'];
    $pwd2 = $_POST['pwd2'];
    $pwd_token = $_POST['pwd_token'];

    try {
        include "connections.php";
        include "pwd_reset_model.php";
        include "pwd_reset_ctrl.php";
        require_once "regex.php";
        require_once 'config_session.php';

        // Tarkistetaan onko käyttäjän syötteet tyhjiä
        if (inputs_empty($pwd, $pwd2)) {
            $_SESSION['reset_error'] = 'Täytä molemmat kentät!';
            header("Location: ../uusi_salasana.php?pwd_token=$pwd_token");
            die();
        }

        // Puhdistetaan käyttäjän syötteet
        $pwd = trim_input($pwd);
        $pwd2 = trim_input($pwd2);

        // Tarkistetaan onko salasanat kelvollisia
        if (!preg_match($patternPwd, $pwd)) {
            $_SESSION['reset_error'] = 'Salasanan tulee olla vähintään 12 merkkiä pitkä.';
            header("Location: ../uusi_salasana.php?pwd_token=$pwd_token");
            die();
        }

        // Tarkistetaan ovatko salasanat samat
        if (!passwords_match($pwd, $pwd2)) {
            $_SESSION['reset_error'] = 'Salasanat eivät täsmää!';
            header("Location: ../uusi_salasana.php?pwd_token=$pwd_token");
            die();
        }

        // Puhdistetaan token vielä kerran, ettei sitä ole manipuloitu
        $pwd_token = trim_input($pwd_token);

        // Haetaan käyttäjän tiedot tietokannasta
        $result = get_token_info($pdo, $pwd_token);
        $user_id = $result['user_id'];

        // hashataan salasana
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        // Päivitetään käyttäjän salasana
        update_password($pdo, $user_id, $pwd);

        // Poistetaan token tietokannasta
        delete_token($pdo, $user_id);

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;

        // Asetetaan salasanan vaihto onnistuneeksi
        $_SESSION['reset_success'] = "Salasanan vaihto onnistui!";
        header("Location: ../uusi_salasana.php?salasana=vaihdettu");
        die();

    } catch (PDOException $e) {
        error_log("PDOException: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }
} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header("Location: ../404.php?virhe");
    die();
}