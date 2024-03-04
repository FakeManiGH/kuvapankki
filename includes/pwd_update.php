<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_pwd = $_POST['old_pwd'];
    $new_pwd = $_POST['new_pwd'];

    try {
        include 'connections.php';
        include "pwd_reset_model.php";
        include "pwd_reset_ctrl.php";
        require_once "regex.php";
        require_once 'config_session.php';


        // Tarkistetaan onko käyttäjän syötteet tyhjiä
        if (inputs_empty($old_pwd, $new_pwd)) {
            $_SESSION['pwd_update_err'] = 'Täytä kaikki kentät!';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // Puhdistetaan käyttäjän syötteet
        $old_pwd = trim_input($old_pwd);
        $new_pwd = trim_input($new_pwd);

        // Haetaan käyttäjän salasana tietokannasta
        $result = get_pwd($pdo, $_SESSION['user_id']);

        // Tarkistetaan onko käyttäjän syöttämä vanha salasana oikein
        if (!password_verify($old_pwd, $result['pwd'])) {
            $_SESSION['pwd_update_err'] = 'Nykyinen salasana ei täsmää!';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // Tarkistetaan onko uusi salasana oikeassa muodossa
        if (!preg_match($patternPwd, $new_pwd)) {
            $_SESSION['pwd_update_err'] = 'Salasanan tulee olla vähintään 12 merkkiä pitkä.';
            header("Location: ../profiili.php?virhe");
            die();
        }

        // hashataan uusi salasana
        $new_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);

        // Päivitetään käyttäjän salasana
        update_password($pdo, $_SESSION['user_id'], $new_pwd);

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;

        // Asetetaan salasanan vaihto onnistuneeksi
        $_SESSION['profile_update_success'] = 'Salasanan vaihto onnistui!';
        header('Location: ../profiili.php?salasana=vaihdettu');
        die();


    } catch (PDOException $e) {
        echo 'Virhe tietokantakyselyssä: ' . $e->getMessage();
    }

} else {
    header('Location: index.php?pääsy=kielletty');
    exit();
}