<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pwd_token = md5(rand(25, 50));

    try {
        include 'connections.php';
        include 'pwd_reset_model.php';
        include 'pwd_reset_ctrl.php';
        require_once 'regex.php';
        require_once "send_email_model.php";
        require_once 'config_session.php';

        // Puhdistetaan luotu token myöhempää vertailua varten (tietoturvasyistä)
        $pwd_token = trim_input($pwd_token);

        // Tarkistetaan onko käyttäjän syötteet tyhjiä
        if (empty($email)) {
            $_SESSION['error_reset'] = 'Anna sähköpostiosoiteesi!';  
            header ("Location: unohtunut_salasana.php?virhe");
            die();
        }
        
        // Tarkistetaan onko käyttäjän syötteet oikeassa muodossa
        if (!preg_match($patternEmail, $email)) {
            $_SESSION['error_reset'] = 'Sähköpostiosoite on väärässä muodossa!';  
            header ("Location: unohtunut_salasana.php?virhe");
            die();
        }

        // Puhdistetaan käyttäjän syötteet
        $email = trim_input($email);

        // Haetaan käyttäjän tiedot tietokannasta
        $result = get_user($pdo, $email);

        // Tarkistetaan onko käyttäjätunnus tai sähköpostiosoite oikea
        if (empty($result)) {
            $_SESSION['error_reset'] = 'Antamaasi sähköpostiosoitetta ei löydy!';  
            header ("Location: unohtunut_salasana.php?virhe");
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
        $_SESSION['reset_link'] = "Salasanan nollauslinkki on lähetetty sähköpostiisi!";

        // Ohjataan käyttäjä takaisin salasanan nollaus-sivulle
        header("Location: unohtunut_salasana.php?linkki=lähetetty.");
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