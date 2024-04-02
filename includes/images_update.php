<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config_session.php';
    include 'connections.php';
    require 'images_model.php';
    require 'images_ctrl.php';

    $user_id = $_SESSION['user_id'];
    $gallery_id = htmlspecialchars($_POST['gallery_id']);
    $image_id = htmlspecialchars($_POST['image_id']);
    $title = trim_input($_POST['title']);
    $description = trim_input($_POST['description']);

    try {

        // Tarkistetaan onko käyttäjä kirjautunut sisään
        if (empty($user_id)) {
            $_SESSION['update_error'] = 'Kirjaudu sisään muokataksesi kuvaa.';
            header('Location: ../kirjaudu.php?kirjautuminen=vaaditaan');
            die();
        }

        // Tarkistetaan onko kuvaa olemassa
        if (!image_exists($pdo, $image_id)) {
            $_SESSION['update_error'] = 'Valitsemaasi kuvaa ei löytynyt.';
            header('Location: ../404.php?galleria=' . $gallery_id . '&kuva=' . $image_id . '&ei_loydy');
            die();
        }

        // Tarkistetaan onko käyttäjällä oikeus muokata kuvaa
        if (!has_write_access($pdo, $user_id, $gallery_id)) {
            $_SESSION['update_error'] = 'Sinulla ei ole oikeutta muokata valitsemaasi kuvaa.';
            header('Location: ../galleria.php?g=' . $gallery_id);
            die();
        }

        // Päivitetään kuvan tiedot
        update_image($pdo, $image_id, $title, $description);

        // Suljetaan tietokantayhteys
        $pdo = null;

        $_SESSION['update_success'] = 'Kuvan ' . $image_id . ' tiedot päivitetty.';
        header('Location: ../galleria.php?g=' . $gallery_id);
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