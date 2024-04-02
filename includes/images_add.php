<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config_session.php';

    $user_id = $_SESSION['user_id'];
    $gallery_id = $_POST['gallery'];

    try {
        include 'connections.php';
        require 'images_model.php';
        require 'images_ctrl.php';

        // Tarkistetaan onko galleria valittu
        if ($gallery_id == 0) {
            $_SESSION['image_upload_error'] = 'Valitse galleria kuville. Jos sinulla ei ole gallerioita, luo sellainen ensin.';
            header('Location: ../lisaa_kuvia.php?virhe=galleria');
            exit();
        }

        // Tarkistetaan onko galleria olemaassa
        if (!gallery_exists($pdo, $gallery_id)) {
            $_SESSION['image_upload_error'] = 'Galleriaa ei ole olemassa.';
            header('Location: ../lisaa_kuvia.php?virhe=galleria');
            exit();
        }

        // Tarkistetaan oikeudet galleriaan
        if (!has_write_access($pdo, $user_id, $gallery_id)) {
            $_SESSION['image_upload_error'] = 'Sinulla ei ole oikeuksia tähän galleriaan.';
            header('Location: ../lisaa_kuvia.php?virhe=oikeudet');
            exit();
        }

        // Check if files were selected
        if (empty($_FILES['images']['name'][0])) {
            $_SESSION['image_upload_error'] = 'Kuvia ei valittu.';
            header('Location: ../lisaa_kuvia.php?virhe=tiedostot');
            exit();
        }
        
        // Luodaan taulukot virheille ja tiedoille
        $image_errors = array();

        $pdo->beginTransaction();

        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
            // Access the file properties
            $fileName = $_FILES['images']['name'][$i];
            $fileTmpName = $_FILES['images']['tmp_name'][$i];
            $fileSize = $_FILES['images']['size'][$i];
            $fileError = $_FILES['images']['error'][$i];
            $fileType = $_FILES['images']['type'][$i];

            // Access the corresponding title and description
            $title = htmlspecialchars($_POST['title'][$i]);
            $description = htmlspecialchars($_POST['description'][$i]);

            // Tarkistetaan onko tiedosto valittu
            if (empty($fileName)) {
                $image_errors[] = 'Tiedostoa ei valittu.';
                continue;
            }

            // Tarkistetaan onko tiedosto liian suuri
            if (!check_file_size($fileSize)) {
                $image_errors[] = 'Tiedosto on liian suuri: ' . $fileName;
                continue;
            }

            // Haetaan tiedoston tyyppi
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            // Tarkistetaan onko tiedostotyyppi sallittu
            if (!check_file_type($fileActualExt)) {
                $image_errors[] = 'Tiedosto on väärää tyyppiä: ' . $fileName;
                continue;
            }

            // Tarkistetaan onko tiedosto latautunut onnistuneesti
            if ($fileError !== 0) {
                $image_errors[] = 'Tiedostoa ei voitu ladata: ' . $fileName;
                continue;
            }

            // Luodaan uusi tiedostonimi
            $fileNameNew = uniqid('', true) . "." . $fileActualExt;
            
            // Määritetään tiedoston tallennuspaikka
            $uploadDir = '../../galleries/' . $gallery_id . '/';
            $fileDestination = $uploadDir . $fileNameNew;
            $fileDestDB = 'galleries/' . $gallery_id . '/' . $fileNameNew;

            // Siirretään tiedosto lopulliseen tallennuspaikkaan
            move_uploaded_file($fileTmpName, $fileDestination);

            // Lisätään kuva tietokantaan
            add_image($pdo, $fileNameNew, $title, $description, $fileDestDB, $user_id, $gallery_id);

            // Tarkistetaan onko tiedosto siirretty
            if (!file_exists($fileDestination)) {
                $image_errors[] = 'Tiedoston siirto epäonnistui: ' . $fileName;
                continue;
            }

        }

        $pdo->commit();

        // Asetetaan kuvien lisäys onnistuneeksi ja palataan galleriaan.
        $_SESSION['image_upload_success'] = 'Kuva(t) lisätty onnistuneesti.';
        if (!empty($image_errors)) {
            $_SESSION['image_upload_errors'] = $image_errors;
        }
        header('Location: ../galleria.php?g=' . $gallery_id . '&lisays=onnistui');
        exit();
     
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("PDOException: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }

} else {
    
    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}