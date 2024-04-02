<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "config_session.php";

    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    try {
        include 'connections.php';
        include "profile_model.php";
        include "profile_ctrl.php";
        include "regex.php";

        // Puhdistetaan käyttäjän syötteet
        $username = trim_input($username);
        $phone = trim_input($phone);
        $email = trim_input($email);
        $pwd = trim_input($pwd);

        // Tarkistetaan onko käyttäjän syötteet tyhjiä
        if (inputs_empty($username, $phone, $email)) {
            $_SESSION['profile_update_err'] = 'Täytä kaikki kentät!';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // Tarkistetaan onko salasana annettu 
        if (empty($pwd)) {
            $_SESSION['profile_update_err'] = 'Anna salasana muokataksesi tietoja.';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // tarkistetaan onko käyttäjätunnus oikeassa muodossa.
        if (!preg_match($patternUser, $username)) {
            $_SESSION['profile_update_err'] = 'Käyttäjätunnuksen tulee olla 5-35 merkkiä pitkä.';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // tarkistetaan onko puhelinnumero oikeassa muodossa.
        if (!preg_match($patternPhone, $phone)) {
            $_SESSION['profile_update_err'] = 'Puhelinnumeron tulee olla 7-15 merkkiä pitkä. Vain numeroita. Esim. 0401234567';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // tarkistetaan onko sähköposti oikeassa muodossa.
        if (!preg_match($patternEmail, $email)) {
            $_SESSION['profile_update_err'] = 'Sähköposti tulee antaa oikeassa muodossa. Esim. esimerkki@gmail.com';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // Haetaan käyttäjän tiedot tietokannasta
        $result = get_user($pdo, $user_id);

        // Tarkistetaan täsmääkö salasana
        if (!password_verify($pwd, $result['pwd'])) {
            $_SESSION['profile_update_err'] = 'Salasana on virheellinen!';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // Tarkistetaan onko käyttäjätunnus tyhjä, käytössä tai muuttunut
        if ($username !== $result['username'] && username_exists($pdo, $username)) {
            $_SESSION['profile_update_err'] = 'Käyttäjätunnus on jo käytössä!';
            header('Location: ../profiili.php?virhe');
            die();
        }
            
        // Tarkistetaan onko käyttäjätunnussähköposti tyhjä, käytössä tai muuttunut
        if ($email !== $result['email'] && email_exists($pdo, $email)) {
            $_SESSION['profile_update_err'] = 'Sähköposti on jo käytössä!';
            header('Location: ../profiili.php?virhe');
            die();
        }

        // Verratataan onko puhelinnumero muuttunut.
        $phone = compare_info($phone, $result['phone']);

        // Päivitetään käyttäjän tiedot
        update_user($pdo, $user_id, $username, $phone, $email);

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;

        // Asetetaan käyttäjän tiedot uudelleen sessioon
        $_SESSION['username'] = $username;
        $_SESSION['phone'] = $phone;
        $_SESSION['email'] = $email;

        // Asetetaan käyttäjän tietojen päivitys onnistuneeksi
        $_SESSION['profile_update_success'] = 'Tietojen päivitys onnistui!';
        header('Location: ../profiili.php?päivitys=onnistui');
        die();


    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }

} else {
    
    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}