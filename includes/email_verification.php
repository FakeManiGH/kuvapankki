<?php

if (isset($_GET['email_token']) && !empty($_GET['email_token'])) {
    $token = $_GET['email_token'];

    try {
        include "connections.php";
        include "register_model.php";
        include "config_session.php";

        $errors = [];

        $result = get_email_token($pdo, $token);

        if (empty($result['email_token'])) {
            $errors['verify_status'] = "Vahvistus epäonnistui. Käytithän oikeaa linkkiä?";
        }
        if (!$token === $result['email_token']) {
            $errors['verify_status'] = "Väärä vahvistuskoodi. Käytithän oikeaa linkkiä?";
        }
        if ($result['verified'] == 1) {
            $errors['verify_status'] = "Sähköpostiosoite on jo vahvistettu. Voit kirjautua sisään.";
        }

        // Tarkistetaan onnistuiko vahvistus
        if ($errors) {
            $_SESSION['errors'] = $errors;
            header("Location: ../vahvistukset.php?vahvistus=epäonnistui");
            die();
        }

        // Asetetaan käyttäjän sähköposti vahvistetuksi
        verify_email($pdo, $token);

        // Nollataan tietokannan yhteys
        $pdo = null;
        $stmt = null;

        // Asetetaan vahvistus onnistuneeksi
        $_SESSION['verify_status'] = "Sähköpostiosoite vahvistettu! Voit nyt kirjautua sisään.";

        header("Location: ../vahvistukset.php?vahvistus=onnistui");
        die();


    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }

} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header("Location: ../404.php?virhe");
    die();
}