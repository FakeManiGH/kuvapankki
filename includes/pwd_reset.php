<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userinfo = $_POST['userinfo'];
    $pwd_token = md5(rand(25, 50));

    try {
        include 'connections.php';
        include 'pwd_reset_model.php';
        require_once 'pwd_reset_ctrl.php';
        require_once "send_email_model.php";

        // Luodaan error-array
        $errors = [];

        // Tarkistetaan onko käyttäjän syötteet tyhjiä
        if (is_input_empty($userinfo)) {
            $errors['empty_input'] = 'Käyttäjätunnus tai sähköpostiosoite puuttuu!';  
        }

        // Tarkistetaan onko käyttäjän syötteet oikeassa muodossa
        if (!is_username_or_email_correct($userinfo)) {
            $errors['incorrect_input'] = 'Käyttäjätunnus tai sähköpostiosoite on väärässä muodossa!';
        }

        // Puhdistetaan käyttäjän syötteet
        $userinfo = trim_input($userinfo);

        // Haetaan käyttäjän tiedot tietokannasta
        $result = get_user($pdo, $userinfo);

        // Tarkistetaan onko käyttäjätunnus tai sähköpostiosoite oikea
        if (empty($result)) {
            $errors['incorrect_input'] = 'Käyttäjätunnusta tai sähköpostiosoitetta ei löydy!';
        }

        // Jos virheitä, palauta takaisin salasanan nollaus-sivulle.
        if ($errors) {
            $_SESSION['errors_reset'] = $errors;

            header ("Location: unohtunut_salasana.php");
            die();
        }

        $user_id = $result['user_id'];
        $username = $result['username'];
        $email = $result['email'];

        // Luodaan salasanan nollaus token
        create_reset_token($pdo, $user_id, $pwd_token);

        // Lähetetään sähköposti käyttäjälle
        send_reset_email($username, $email, $pwd_token);

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;

        // Asetetaan salasanan nollaus onnistuneeksi
        $_SESSION['reset_status'] = "Salasanan nollauslinkki on lähetetty sähköpostiisi!";

        // Ohjataan käyttäjä takaisin salasanan nollaus-sivulle
        header("Location: unohtunut_salasana.php?linkki=lähetetty.");
        die();

    } catch (PDOException $e) {
        echo "Virhe tietokantakyselyssä: " . $e->getMessage();
    }

} else {
    header("Location: ../unohtunut_salasana.php?pääsy=estetty");
    die();
}