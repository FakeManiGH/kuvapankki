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

        // Tarkistetaan onko käyttäjätunnus tyhjä, käytössä tai muuttunut
        if (!empty($username) && $username !== $result['username'] && username_exists($pdo, $username)) {
            $_SESSION['profile_update_err'] = 'Käyttäjätunnus on jo käytössä!';
            header('Location: ../profiili.php?virhe');
            die();
        } else {
            $username = compare_info($username, $result['username']);
        }
            
        // Tarkistetaan onko käyttäjätunnussähköposti tyhjä, käytössä tai muuttunut
        if (!empty($email) && $email !== $result['email'] && email_exists($pdo, $email)) {
            $_SESSION['profile_update_err'] = 'Sähköpostiosoite on jo käytössä!';
            header('Location: ../profiili.php?virhe');
            die();
        } else {
            $email = compare_info($email, $result['email']);
        }  

        // Verratataan onko puhelinnumero muuttunut.
        $phone = compare_info($phone, $result['phone']);

        // Tarkistetaan täsmääkö salasana
        if (!empty($pwd)) {
            if (!password_verify($pwd, $result['pwd'])) {
                $_SESSION['profile_update_err'] = 'Salasana on virheellinen!';
                header('Location: ../profiili.php?virhe');
                die();
            }
        }

        // Päivitetään käyttäjän tiedot
        update_user($pdo, $user_id, $username, $phone, $email);

        // Nollataan tietokantayhteys ja poistetaan muuttujat
        $pdo = null;
        $stmt = null;

        // Asetetaan käyttäjän tietojen päivitys onnistuneeksi
        $_SESSION['profile_update_success'] = 'Tietojen päivitys onnistui!';

        // Asetetaan käyttäjän tiedot uudelleen sessioon
        $_SESSION['username'] = $username;
        $_SESSION['phone'] = $phone;
        $_SESSION['email'] = $email;


        header('Location: ../profiili.php?onnistui');
        die();


    } catch (PDOException $e) {
        die("Tietokantavirhe: " . $e->getMessage());
    }

} else {
    header('Location: ../index.php?pääsy=kielletty');
    die();
}